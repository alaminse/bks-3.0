<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\FeaturedImage;
use App\Models\Package;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    // Homepage
    public function index()
    {
        // Get packages
        $packages = Package::active()
            ->latest()
            ->get();

        return view('welcome', compact('packages'));

    }

    // Package details page
    public function packageShow($slug)
    {
        $package = Package::whereSlug($slug)->firstOrFail();
        abort_if($package->is_active !== true, 404);

        return view('show', compact('package'));
    }

    // Contact form submit
    // public function contactStore(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'email' => 'required|email|max:150',
    //         'message' => 'required|string|max:2000',
    //     ]);

    //     ContactMessage::create($request->only('name', 'email', 'message'));

    //     return back()->with('success', 'Thank you! We will contact you soon.');
    // }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'message'   => 'required|string|min:10|max:1000',
        ]);

        ContactMessage::create($validated);

        // Check if it's an AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.'
            ]);
        }

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
