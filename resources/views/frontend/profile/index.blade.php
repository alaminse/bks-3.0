@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')

{{-- PROFILE HEADER --}}
<div class="pf-header">
    <div class="pf-header-left">
        <div class="pf-avatar-wrap">
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="pf-avatar" id="avatarPreview">
            <label for="avatarInput" class="pf-avatar-cam" title="Change avatar">
                <i class="bi bi-camera-fill"></i>
            </label>
            <form action="{{ route('profile.upload.avatar') }}" method="POST"
                enctype="multipart/form-data" id="avatarForm">
                @csrf
                <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;">
            </form>
        </div>
        <div class="pf-header-info">
            <div class="pf-header-name">
                {{ $user->full_name }}
                @if($user->email_verified_at)
                <span class="pf-badge-verified">
                    <i class="bi bi-check-circle-fill"></i> Verified
                </span>
                @endif
            </div>
            <div class="pf-header-email">{{ $user->email }}</div>
            <div class="pf-header-since">Member since {{ $user->created_at->format('d M Y') }}</div>
        </div>
    </div>
    <div class="pf-header-right">
        <div class="pf-bal-label">Wallet balance</div>
        <div class="pf-bal-val">${{ number_format($totalEarnings, 2) }}</div>
        <div class="pf-bal-sub">USDT · Available</div>
    </div>
</div>

{{-- STATS --}}
<div class="pf-stats">
    <div class="pf-stat">
        <div class="pf-stat-label">Total referrals</div>
        <div class="pf-stat-val">{{ $user->total_referrals }}</div>
    </div>
    <div class="pf-stat">
        <div class="pf-stat-label">Active</div>
        <div class="pf-stat-val" style="color:var(--green);">{{ $user->getActiveReferralsCount() }}</div>
    </div>
    <div class="pf-stat">
        <div class="pf-stat-label">Ref. earned</div>
        <div class="pf-stat-val" style="color:var(--blue);">${{ number_format($user->total_referral_earnings, 2) }}</div>
    </div>
    <div class="pf-stat">
        <div class="pf-stat-label">Age</div>
        <div class="pf-stat-val" style="color:var(--gold);">{{ $user->age ?? '—' }}</div>
    </div>
</div>

{{-- PERSONAL INFORMATION --}}
<div class="pf-card">
    <div class="pf-card-head">
        <div>
            <div class="pf-card-title">Personal information</div>
            <div class="pf-card-sub">Update your name and personal details</div>
        </div>
    </div>
    <div class="pf-card-body">
        <form action="{{ route('profile.update.basic') }}" method="POST">
            @csrf
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">First name</label>
                    <input type="text" name="name"
                        class="pf-input @error('name') pf-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="pf-error">{{ $message }}</div>@enderror
                </div>
                <div class="pf-field">
                    <label class="pf-label">Last name</label>
                    <input type="text" name="last_name"
                        class="pf-input @error('last_name') pf-invalid @enderror"
                        value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')<div class="pf-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">Email address</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-envelope pf-input-icon"></i>
                        <input type="email" name="email"
                            class="pf-input pf-has-icon @error('email') pf-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                    </div>
                    @error('email')<div class="pf-error">{{ $message }}</div>@enderror
                </div>
                <div class="pf-field">
                    <label class="pf-label">Date of birth</label>
                    <input type="date" name="date_of_birth"
                        class="pf-input"
                        value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                        style="color-scheme:dark;">
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Gender</label>
                <select name="gender" class="pf-input">
                    <option value="">Select gender</option>
                    @foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('gender',$user->gender)==$v ? 'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="pf-btn pf-btn-primary">
                <i class="bi bi-check-lg"></i> Save changes
            </button>
        </form>
    </div>
</div>

{{-- CONTACT & LOCATION --}}
<div class="pf-card">
    <div class="pf-card-head">
        <div>
            <div class="pf-card-title">Contact & location</div>
            <div class="pf-card-sub">Where you're based and how to reach you</div>
        </div>
    </div>
    <div class="pf-card-body">
        <form action="{{ route('profile.update.details') }}" method="POST">
            @csrf
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">Phone</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-telephone pf-input-icon"></i>
                        <input type="tel" name="phone" class="pf-input pf-has-icon"
                            placeholder="+1 (555) 000-0000"
                            value="{{ old('phone', $user->profile->phone ?? '') }}">
                    </div>
                </div>
                <div class="pf-field">
                    <label class="pf-label">Country</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-globe2 pf-input-icon"></i>
                        <input type="text" name="country" class="pf-input pf-has-icon"
                            placeholder="United States"
                            value="{{ old('country', $user->profile->country ?? '') }}">
                    </div>
                </div>
            </div>
            <div class="pf-grid3">
                <div class="pf-field">
                    <label class="pf-label">State</label>
                    <input type="text" name="state" class="pf-input"
                        placeholder="California"
                        value="{{ old('state', $user->profile->state ?? '') }}">
                </div>
                <div class="pf-field">
                    <label class="pf-label">City</label>
                    <input type="text" name="city" class="pf-input"
                        placeholder="San Francisco"
                        value="{{ old('city', $user->profile->city ?? '') }}">
                </div>
                <div class="pf-field">
                    <label class="pf-label">Postal code</label>
                    <input type="text" name="postal_code" class="pf-input"
                        placeholder="94105"
                        value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Address</label>
                <input type="text" name="address" class="pf-input"
                    placeholder="123 Market St"
                    value="{{ old('address', $user->profile->address ?? '') }}">
            </div>
            <div class="pf-field">
                <label class="pf-label">Occupation</label>
                <div class="pf-input-wrap">
                    <i class="bi bi-briefcase pf-input-icon"></i>
                    <input type="text" name="occupation" class="pf-input pf-has-icon"
                        placeholder="Software Engineer"
                        value="{{ old('occupation', $user->profile->occupation ?? '') }}">
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Bio</label>
                <textarea name="bio" class="pf-input pf-textarea"
                    placeholder="Tell us about yourself...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
            </div>
            <button type="submit" class="pf-btn pf-btn-primary">
                <i class="bi bi-check-lg"></i> Save changes
            </button>
        </form>
    </div>
</div>

{{-- SOCIAL LINKS --}}
<div class="pf-card">
    <div class="pf-card-head">
        <div>
            <div class="pf-card-title">Social links</div>
            <div class="pf-card-sub">Connect your social profiles</div>
        </div>
    </div>
    <div class="pf-card-body">
        <form action="{{ route('profile.update.social') }}" method="POST">
            @csrf
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">Facebook</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-facebook pf-input-icon"></i>
                        <input type="url" name="facebook_url" class="pf-input pf-has-icon"
                            placeholder="https://facebook.com/..."
                            value="{{ old('facebook_url', $user->profile->facebook_url ?? '') }}">
                    </div>
                </div>
                <div class="pf-field">
                    <label class="pf-label">Twitter / X</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-twitter-x pf-input-icon"></i>
                        <input type="url" name="twitter_url" class="pf-input pf-has-icon"
                            placeholder="https://twitter.com/..."
                            value="{{ old('twitter_url', $user->profile->twitter_url ?? '') }}">
                    </div>
                </div>
            </div>
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">Instagram</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-instagram pf-input-icon"></i>
                        <input type="url" name="instagram_url" class="pf-input pf-has-icon"
                            placeholder="https://instagram.com/..."
                            value="{{ old('instagram_url', $user->profile->instagram_url ?? '') }}">
                    </div>
                </div>
                <div class="pf-field">
                    <label class="pf-label">LinkedIn</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-linkedin pf-input-icon"></i>
                        <input type="url" name="linkedin_url" class="pf-input pf-has-icon"
                            placeholder="https://linkedin.com/in/..."
                            value="{{ old('linkedin_url', $user->profile->linkedin_url ?? '') }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="pf-btn pf-btn-primary">
                <i class="bi bi-check-lg"></i> Save social links
            </button>
        </form>
    </div>
</div>

{{-- PASSWORD & SECURITY --}}
<div class="pf-card">
    <div class="pf-card-head">
        <div>
            <div class="pf-card-title">Password & security</div>
            <div class="pf-card-sub">Keep your account safe</div>
        </div>
    </div>
    <div class="pf-card-body">
        <form action="{{ route('profile.change.password') }}" method="POST">
            @csrf
            <div class="pf-field">
                <label class="pf-label">Current password</label>
                <div class="pf-input-wrap">
                    <i class="bi bi-lock pf-input-icon"></i>
                    <input type="password" name="current_password"
                        class="pf-input pf-has-icon @error('current_password') pf-invalid @enderror"
                        placeholder="••••••••" required>
                </div>
                @error('current_password')<div class="pf-error">{{ $message }}</div>@enderror
            </div>
            <div class="pf-grid2">
                <div class="pf-field">
                    <label class="pf-label">New password</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-key pf-input-icon"></i>
                        <input type="password" name="new_password"
                            class="pf-input pf-has-icon @error('new_password') pf-invalid @enderror"
                            placeholder="••••••••" required>
                    </div>
                    @error('new_password')<div class="pf-error">{{ $message }}</div>@enderror
                </div>
                <div class="pf-field">
                    <label class="pf-label">Confirm password</label>
                    <div class="pf-input-wrap">
                        <i class="bi bi-key pf-input-icon"></i>
                        <input type="password" name="new_password_confirmation"
                            class="pf-input pf-has-icon"
                            placeholder="••••••••" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="pf-btn pf-btn-primary">
                <i class="bi bi-shield-check"></i> Update password
            </button>
        </form>
    </div>
</div>

{{-- DANGER ZONE --}}
<div class="pf-card pf-danger-card">
    <div class="pf-card-head pf-danger-head">
        <div>
            <div class="pf-card-title pf-danger-title">
                <i class="bi bi-exclamation-triangle-fill"></i> Danger zone
            </div>
        </div>
    </div>
    <div class="pf-card-body">
        <p class="pf-danger-desc">
            Permanently delete your account and all associated data. This action is irreversible and cannot be undone.
        </p>
        <button type="button" class="pf-btn pf-btn-danger"
            data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="bi bi-trash3"></i> Delete my account
        </button>
    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('profile.delete') }}" method="POST" id="deleteAccountForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size:0.95rem !important;font-weight:700 !important;color:var(--red) !important;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.2);border-left:3px solid var(--red);border-radius:10px;padding:12px 14px;margin-bottom:18px;font-size:0.82rem;color:var(--muted);line-height:1.6;display:flex;gap:8px;">
                        <i class="bi bi-info-circle-fill" style="color:var(--red);flex-shrink:0;margin-top:2px;"></i>
                        <span>This is permanent. All earnings, packages, referrals and data will be removed.</span>
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Confirm your password</label>
                        <div class="pf-input-wrap">
                            <i class="bi bi-lock pf-input-icon"></i>
                            <input type="password" name="password"
                                class="pf-input pf-has-icon"
                                placeholder="Enter your password" required>
                        </div>
                        @error('password')<div class="pf-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:10px;background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.15);border-radius:9px;padding:12px 14px;margin-top:12px;">
                        <input type="checkbox" id="confirmDelete" required
                            style="width:16px;height:16px;accent-color:var(--red);flex-shrink:0;cursor:pointer;margin-top:2px;">
                        <label for="confirmDelete" style="font-size:0.82rem;color:var(--muted);cursor:pointer;line-height:1.55;">
                            I understand this cannot be reversed and all my data will be permanently deleted.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pf-btn pf-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="pf-btn pf-btn-danger">
                        <i class="bi bi-trash3-fill"></i> Delete permanently
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ══ PROFILE — COINBASE STYLE ══ */

/* Header */
.pf-header {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.pf-header-left  { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
.pf-avatar-wrap  { position: relative; flex-shrink: 0; }
.pf-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    object-fit: cover; display: block;
    border: 2px solid rgba(255,255,255,0.12);
}
.pf-avatar-cam {
    position: absolute; bottom: 0; right: 0;
    width: 22px; height: 22px; border-radius: 50%;
    background: var(--card2); border: 1px solid var(--border2);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.6rem; cursor: pointer; transition: all 0.2s;
    color: var(--muted);
}
.pf-avatar-cam:hover { background: var(--accent); color: #000; border-color: var(--accent); }
.pf-header-name {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem; font-weight: 800;
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    margin-bottom: 4px;
}
.pf-badge-verified {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.65rem; font-weight: 600;
    background: rgba(34,197,94,0.1); color: var(--green);
    border: 1px solid rgba(34,197,94,0.25);
    padding: 2px 8px; border-radius: 99px;
}
.pf-header-email { font-size: 0.82rem; color: var(--muted); margin-bottom: 2px; }
.pf-header-since { font-size: 0.72rem; color: var(--muted); }
.pf-header-right { text-align: right; flex-shrink: 0; }
.pf-bal-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-bottom: 4px; }
.pf-bal-val   { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--accent); line-height: 1; }
.pf-bal-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 3px; }

/* Stats */
.pf-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 20px;
}
.pf-stat {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 16px;
    transition: background 0.2s;
}
.pf-stat:hover { background: var(--card2); }
.pf-stat-label { font-size: 0.68rem; color: var(--muted); margin-bottom: 6px; }
.pf-stat-val   { font-family: 'Syne', sans-serif; font-size: 1.2rem; font-weight: 800; }

/* Card */
.pf-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 10px;
}
.pf-card-head {
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.pf-card-title { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 700; margin-bottom: 2px; }
.pf-card-sub   { font-size: 0.75rem; color: var(--muted); }
.pf-card-body  { padding: 22px; }

/* Danger card */
.pf-danger-card { border-color: rgba(239,68,68,0.25) !important; }
.pf-danger-head { border-bottom-color: rgba(239,68,68,0.2) !important; }
.pf-danger-title { color: var(--red) !important; display: flex; align-items: center; gap: 7px; }
.pf-danger-desc  { font-size: 0.85rem; color: var(--muted); margin-bottom: 16px; line-height: 1.65; }

/* Form */
.pf-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; }
.pf-grid3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px; }
.pf-field { margin-bottom: 14px; }
.pf-field:last-of-type { margin-bottom: 0; }
.pf-grid2 .pf-field,
.pf-grid3 .pf-field  { margin-bottom: 0; }

.pf-label {
    display: block; font-size: 0.75rem; font-weight: 600;
    color: var(--muted); margin-bottom: 6px;
}
.pf-input-wrap { position: relative; }
.pf-input-icon {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%); color: var(--muted);
    font-size: 0.88rem; pointer-events: none;
}
.pf-input {
    width: 100%;
    background: var(--card2) !important;
    border: 1px solid var(--border2) !important;
    border-radius: 9px !important;
    color: var(--text) !important;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem; padding: 9px 14px;
    outline: none; -webkit-appearance: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.pf-input.pf-has-icon { padding-left: 36px; }
.pf-input::placeholder { color: var(--muted) !important; }
.pf-input:focus {
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 3px rgba(0,245,212,0.1) !important;
}
.pf-input.pf-invalid { border-color: var(--red) !important; }
.pf-input option      { background: var(--card2); color: var(--text); }
.pf-textarea { resize: vertical; min-height: 80px; height: 80px; }
.pf-error { font-size: 0.72rem; color: var(--red); margin-top: 4px; }

/* Buttons */
.pf-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; font-weight: 600;
    padding: 9px 18px; border-radius: 9px;
    border: none; cursor: pointer; transition: all 0.15s;
    margin-top: 6px;
}
.pf-btn-primary { background: var(--accent); color: #000; }
.pf-btn-primary:hover { opacity: 0.9; }
.pf-btn-ghost   { background: transparent; color: var(--muted); border: 1px solid var(--border2) !important; }
.pf-btn-ghost:hover { background: var(--card2); color: var(--text); }
.pf-btn-danger  { background: rgba(239,68,68,0.1); color: var(--red); border: 1px solid rgba(239,68,68,0.25) !important; }
.pf-btn-danger:hover { background: rgba(239,68,68,0.18); }

/* Responsive */
@media(max-width: 900px) {
    .pf-stats { grid-template-columns: 1fr 1fr; }
}
@media(max-width: 768px) {
    .pf-header { flex-direction: column; align-items: flex-start; gap: 14px; padding: 18px; }
    .pf-header-right { order: -1; align-self: flex-end; }
    .pf-bal-val { font-size: 1.3rem; }
    .pf-grid2 { grid-template-columns: 1fr; gap: 0; }
    .pf-grid2 .pf-field { margin-bottom: 14px; }
    .pf-grid3 { grid-template-columns: 1fr 1fr; }
    .pf-card-body { padding: 16px; }
    .pf-card-head { padding: 14px 16px; }
}
@media(max-width: 480px) {
    .pf-stats { grid-template-columns: 1fr 1fr; gap: 6px; }
    .pf-stat  { padding: 12px 14px; }
    .pf-grid3 { grid-template-columns: 1fr; gap: 0; }
    .pf-grid3 .pf-field { margin-bottom: 14px; }
    .pf-header-left { gap: 12px; }
    .pf-avatar { width: 52px; height: 52px; }
    .pf-header-name { font-size: 0.95rem; }
    .pf-header-right { display: none; }
}
</style>

<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    Swal.fire({
        title: 'Upload new avatar?',
        text: 'Your profile picture will be updated.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
    }).then(r => {
        if (r.isConfirmed) {
            const reader = new FileReader();
            reader.onload = e => { document.getElementById('avatarPreview').src = e.target.result; };
            reader.readAsDataURL(file);
            document.getElementById('avatarForm').submit();
        } else {
            e.target.value = '';
        }
    });
});

document.getElementById('deleteAccountForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!this.querySelector('input[name="password"]').value) {
        Swal.fire({ icon: 'error', title: 'Password required', text: 'Please enter your password.' });
        return;
    }
    const self = this;
    Swal.fire({
        title: 'Are you absolutely sure?',
        text: 'This will permanently delete your account and all data!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete permanently',
    }).then(r => { if (r.isConfirmed) self.submit(); });
});
</script>
@endpush
