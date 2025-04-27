<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::with(['user', 'agency'])->paginate(10);
        return view('agents.index', compact('agents'));
    }

    public function create()
    {
        $agencies = Agency::all();
        return view('agents.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'license_number' => 'required|string|max:50',
            'years_experience' => 'required|integer|min:0',
            'bio' => 'nullable|string',
            'specialties' => 'nullable|array',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign the agent role
            $agentRole = Role::where('name', 'agent')->first();
            if ($agentRole) {
                $user->roles()->attach($agentRole->id);
            }

            // Handle profile image upload
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('agents', 'public');
            }

            // Create the agent profile
            $agent = Agent::create([
                'user_id' => $user->id,
                'agency_id' => $request->agency_id,
                'license_number' => $validated['license_number'],
                'years_experience' => $validated['years_experience'],
                'bio' => $validated['bio'] ?? null,
                'specialties' => $validated['specialties'] ?? [],
                'profile_image' => $profileImagePath,
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('agents.show', $agent->id)
                ->with('success', 'Agent created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create agent: ' . $e->getMessage()]);
        }
    }


    public function show(Agent $agent)
    {
        $agent->load(['user', 'agency', 'properties']);
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $agencies = Agency::all();
        return view('agents.edit', compact('agent', 'agencies'));
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($agent->user_id),
            ],
            'license_number' => [
                'required',
                'string',
                Rule::unique('agents')->ignore($agent->id),
            ],
            'agency_id' => 'nullable|exists:agencies,id',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);

        // Update user
        $agent->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Handle password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            
            $agent->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Handle profile image update
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($agent->profile_image) {
                Storage::disk('public')->delete($agent->profile_image);
            }
            
            $profileImagePath = $request->file('profile_image')->store('agents', 'public');
            $agent->profile_image = $profileImagePath;
        }

        // Update agent
        $agent->update([
            'agency_id' => $request->agency_id,
            'license_number' => $request->license_number,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
            'phone_number' => $request->phone_number,
            'is_active' => $request->has('is_active'),
            'years_of_experience' => $request->years_of_experience ?? 0,
        ]);

        return redirect()->route('agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        // Delete profile image if exists
        if ($agent->profile_image) {
            Storage::disk('public')->delete($agent->profile_image);
        }

        // Delete user (will cascade delete agent due to foreign key constraint)
        $agent->user->delete();

        return redirect()->route('agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
