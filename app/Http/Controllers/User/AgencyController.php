<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::withCount('agents')->paginate(10);
        return view('agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('agencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'website' => 'nullable|url|max:255',
            'is_verified' => 'boolean',
        ]);

        // Generate slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        // Ensure slug is unique
        while (Agency::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agencies', 'public');
        }

        // Create agency
        $agency = Agency::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'logo' => $logoPath,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website' => $request->website,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('agencies.index')
            ->with('success', 'Agency created successfully.');
    }

    public function show(Agency $agency)
    {
        $agency->load(['agents.user', 'properties']);
        return view('agencies.show', compact('agency'));
    }

    public function edit(Agency $agency)
    {
        return view('agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'website' => 'nullable|url|max:255',
            'is_verified' => 'boolean',
        ]);

        // Handle logo update
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }
            
            $logoPath = $request->file('logo')->store('agencies', 'public');
            $agency->logo = $logoPath;
        }

        // Update agency
        $agency->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website' => $request->website,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('agencies.index')
            ->with('success', 'Agency updated successfully.');
    }

    public function destroy(Agency $agency)
    {
        // Delete logo if exists
        if ($agency->logo) {
            Storage::disk('public')->delete($agency->logo);
        }

        // Delete agency
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Agency deleted successfully.');
    }
}
