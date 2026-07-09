<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReferralService;
use App\Services\WalletService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $redirectTo = '/dashboard';

    protected $referralService;

    protected $walletService;

    public function __construct(ReferralService $referralService, WalletService $walletService)
    {
        $this->middleware('guest');
        $this->referralService = $referralService;
        $this->walletService = $walletService;
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $referralCode = $request->query('ref');
        $referrer = null;

        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
        }

        return view('auth.register', compact('referralCode', 'referrer'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        $referralCode = $request->input('ref');
        $referrer = null;

        if ($referralCode) {
            $referrer = User::where('referral_code', $referralCode)->first();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'referralCode' => $referralCode,
                    'referrer' => $referrer,
                ]);
        }

        DB::beginTransaction();

        try {
            // Create user
            $user = $this->create($request->all());

            event(new Registered($user));

            // Assign role
            $user->assignRole('user');

            // Signup bonus
            $this->walletService->credit(
                userId: $user->id,
                amount: 0,
                type: 'deposit',
                referenceType: 'SignupBonus',
                description: 'Welcome Bonus'
            );

            // Login user
            Auth::login($user);

            DB::commit();

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectTo);

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Registration failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', $e->getMessage()) // Change to generic message in production
                ->withInput()
                ->with([
                    'referralCode' => $referralCode,
                    'referrer' => $referrer,
                ]);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'ref' => 'nullable|string|exists:users,referral_code',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['ref']) && $data['ref']) {
            $this->referralService->createReferral($user->id, $data['ref']);
        }

        return $user;
    }

    /**
     * The user has been registered.
     * Override this method if you need custom logic after registration
     */
    protected function registered(Request $request, $user)
    {
        // Custom logic after registration (if needed)
        return null;
    }

    /**
     * Get the post register / login redirect path.
     */
    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}
