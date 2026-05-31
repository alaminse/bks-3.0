<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FeaturedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FeaturedImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FeaturedImage::query();

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $images = $query->orderBy('order', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(12);

        $stats = [
            'total' => FeaturedImage::count(),
            'active' => FeaturedImage::where('status', 'active')->count(),
            'inactive' => FeaturedImage::where('status', 'inactive')->count(),
        ];

        return view('backend.images.index', compact('images', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'link_url' => 'nullable|url|max:500',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('featured_images', $filename, 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        FeaturedImage::create($validated);

        return redirect()
            ->route('backend.images.index')
            ->with('success', 'Featured image uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeaturedImage $image)
    {
        return view('backend.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeaturedImage $image)
    {
        return view('backend.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeaturedImage $image)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'link_url' => 'nullable|url|max:500',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($image->image_path && file_exists(public_path($image->image_path))) {
                unlink(public_path($image->image_path));
            }

            $imageFile = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('featured_images', $filename, 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $image->update($validated);

        return redirect()
            ->route('backend.images.index')
            ->with('success', 'Featured image updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeaturedImage $image)
    {
        // Delete image file
        if ($image->image_path && file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }

        $image->delete();

        return redirect()
            ->route('backend.images.index')
            ->with('success', 'Featured image deleted successfully!');
    }
}
