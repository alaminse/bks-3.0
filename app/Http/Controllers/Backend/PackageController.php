<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('permission:package-list|package-create|package-edit|package-delete', ['only' => ['index','store']]);
        $this->middleware('permission:package-create', ['only' => ['create','store']]);
        $this->middleware('permission:package-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:package-delete', ['only' => ['destroy']]);
    }

    /** 📌 Package List */
    public function index()
    {
        $packages = Package::orderBy('sort_order')->paginate(10);

        return view('backend.packages.index', compact('packages'));
    }

    /** 📌 Create Form */
    public function create()
    {
        return view('backend.packages.create');
    }

    /** 📌 Store Package */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:packages,slug',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'daily_tasks'      => 'nullable|integer|min:0',
            'daily_earning'    => 'nullable|numeric|min:0',
            'per_task_earning' => 'nullable|numeric|min:0',
            'duration_days'    => 'nullable|integer|min:0',
            'features'         => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'nullable|integer',
        ]);

        Package::create([
            'name'             => $request->name,
            'slug'             => $request->slug ?: Str::slug($request->name),
            'description'      => $request->description,
            'price'            => $request->price,
            'daily_tasks'      => $request->daily_tasks,
            'daily_earning'    => $request->daily_earning,
            'per_task_earning' => $request->per_task_earning,
            'duration_days'    => $request->duration_days,
            'features'         => $request->features,
            'is_active'        => $request->is_active ?? 1,
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('backend.packages.index')
            ->with('success', 'Package created successfully');
    }

    /** 📌 Edit Form */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('backend.packages.edit', compact('package'));
    }

    /** 📌 Update Package */
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:packages,slug,' . $id,
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'daily_tasks'      => 'nullable|integer|min:0',
            'daily_earning'    => 'nullable|numeric|min:0',
            'per_task_earning' => 'nullable|numeric|min:0',
            'duration_days'    => 'nullable|integer|min:0',
            'features'         => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'nullable|integer',
        ]);

        $package->update([
            'name'             => $request->name,
            'slug'             => $request->slug ?: Str::slug($request->name),
            'description'      => $request->description,
            'price'            => $request->price,
            'daily_tasks'      => $request->daily_tasks,
            'daily_earning'    => $request->daily_earning,
            'per_task_earning' => $request->per_task_earning,
            'duration_days'    => $request->duration_days,
            'features'         => $request->features,
            'is_active'        => $request->is_active ?? 1,
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('backend.packages.index')
            ->with('success', 'Package updated successfully');
    }

    /** 📌 Show Package */
    public function show($id)
    {
        $package = Package::findOrFail($id);
        return view('backend.packages.show', compact('package'));
    }

    /** 📌 Delete Package */
    public function destroy($id)
    {
        Package::findOrFail($id)->delete();

        return redirect()
            ->route('backend.packages.index')
            ->with('success', 'Package deleted successfully');
    }

    /** 📌 Active / Inactive Toggle */
    public function status($id)
    {
        $package = Package::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        return back()->with('success', 'Status updated');
    }
}
