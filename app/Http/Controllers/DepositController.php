<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepositCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    /**
     * Deposit page
     */
    public function deposit()
    {
        $wallet = Auth::user()?->wallet;

        // User recent deposits
        $deposits = Deposit::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.wallet.deposit', compact('wallet', 'deposits'));
    }

    /**
     * Deposit request
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount'            => 'required|numeric|min:10|max:10000',
            'payment_method'    => 'required|in:binance_pay,binance_p2p',
            'transaction_id'    => 'required|string|min:10|max:100|unique:deposits,transaction_id',
            'payment_proof'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('temp', 'public');
            session(['payment_proof_preview' => $path]);
        }

        try {
            // Payment proof upload
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('deposits', 'public');
            }

            $user = Auth::user();
            $isDemoUser = $user->isDemo ?? false;
            // Deposit record
            $deposit = Deposit::create([
                'user_id'           => $user->id,
                'amount'            => $request->amount,
                'payment_method'    => $request->payment_method,
                'transaction_id'    => $request->transaction_id,
                'payment_proof'     => $proofPath,
                'status'            => $isDemoUser ? 'approved' : 'pending',
                'reference_number'  => 'DEP-' . strtoupper(Str::random(10)),
                'approved_at'       => $isDemoUser ? now() : null,
                'approved_by'       => $isDemoUser ? (Auth::check() ? Auth::id() : 1) : null // Use current admin or default to admin with ID 1
            ]);

            if ($isDemoUser) {
                app(WalletService::class)->credit(
                    userId: $deposit->user_id,
                    amount: $deposit->amount,
                    type: 'deposit',
                    referenceType: 'Deposit',
                    referenceId: $deposit->id,
                    description: "Auto-approved deposit - {$deposit->reference_number}"
                );
            }

            if ($request->hasFile('payment_proof')) {
                Storage::disk('public')->delete(session('payment_proof_preview'));
            }

            session()->forget('payment_proof_preview');

            Mail::to('worldking4569956@gmail.com')->send(new DepositCreated($deposit, $user));

        $message = $isDemoUser
            ? 'Deposit request submitted and automatically approved. Amount credited to your wallet.'
            : 'Deposit request submitted successfully. Please wait for admin approval.';

        return redirect()->route('wallet.deposit')->with('success', $message);
            // return redirect()->route('wallet.deposit')
            //     ->with('success', 'Deposit request submitted successfully. Please wait for admin approval.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit deposit request: ' . $e->getMessage());
        }
    }
}
