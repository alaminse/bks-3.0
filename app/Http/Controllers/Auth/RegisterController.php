<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ReferralService;
use App\Services\WalletService;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
        // Validate
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            // Get referrer data for redirect
            $referralCode = $request->input('ref');
            $referrer = null;

            if ($referralCode) {
                $referrer = User::where('referral_code', $referralCode)->first();
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with(compact('referralCode', 'referrer'));
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
            Auth::guard()->login($user);

            DB::commit();

            // Check if there's a custom registered response
            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectTo);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            // Get referrer data for redirect
            $referralCode = $request->input('ref');
            $referrer = null;

            if ($referralCode) {
                $referrer = User::where('referral_code', $referralCode)->first();
            }

            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput()
                ->with(compact('referralCode', 'referrer'));
        }
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required','string','min:2','max:50','regex:/^[a-zA-Z\s]+$/'],
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
