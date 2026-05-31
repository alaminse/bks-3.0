<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTaskSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile page
     */
    public function index()
    {
        $user = Auth::user()->load('profile');

        // Create profile if not exists
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        $totalEarnings = UserTaskSubmission::where('user_id', $user->id)
                            ->where('status', 'approved')
                            ->sum('reward_amount');

        return view('frontend.profile.index', compact('user', 'totalEarnings'));
    }

    /**
     * Update basic info
     */
    public function updateBasicInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);

        Auth::user()->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'Basic information updated successfully!');
    }

    /**
     * Update profile details
     */
    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        Auth::user()->profile()->updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'phone', 'country', 'state', 'city',
                'address', 'postal_code', 'occupation', 'bio'
            ])
        );

        return back()->with('success', 'Profile details updated successfully!');
    }

    /**
     * Update social links
     */
    public function updateSocialLinks(Request $request)
    {
        $request->validate([
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);

        Auth::user()->profile()->updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'facebook_url', 'twitter_url',
                'instagram_url', 'linkedin_url'
            ])
        );

        return back()->with('success', 'Social links updated successfully!');
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar updated successfully!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect']);
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Soft delete user
        $user->delete();

        Auth::logout();

        return redirect()->route('login')->with('success', 'Account deleted successfully!');
    }
}
