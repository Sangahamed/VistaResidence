<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $properties = Property::where('owner_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'status' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'features' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
        ]);

        $property = Property::create([
            ...$validated,
            'features' => $validated['features'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'owner_id' => auth()->id(),
            'company_id' => auth()->user()->companies->first()->id ?? null,
        ]);

        return redirect()->route('properties.media', $property)
            ->with('success', 'Propriété créée avec succès. Vous pouvez maintenant ajouter des médias.');
    }

    public function show(Property $property)
    {
        $this->authorizeAccess($property);
        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorizeAccess($property);
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeAccess($property);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'status' => 'required|string',
            'price' => 'required|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'features' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'newVideos.*' => 'mimes:mp4,mov,avi|max:20480',
        ]);

        // Supprimer les images cochées
        $images = $property->images ?? [];
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $index) {
                if (isset($images[$index])) {
                    Storage::disk('public')->delete($images[$index]['path']);
                    unset($images[$index]);
                }
            }
            $images = array_values($images); // réindexer
        }

        // Supprimer les vidéos cochées
        $videos = $property->videos ?? [];
        if ($request->has('delete_videos')) {
            foreach ($request->delete_videos as $index) {
                if (isset($videos[$index])) {
                    Storage::disk('public')->delete($videos[$index]['path']);
                    unset($videos[$index]);
                }
            }
            $videos = array_values($videos); // réindexer
        }

        // Ajouter les nouvelles images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('properties/images', 'public');
                $images[] = [
                    'filename' => basename($path),
                    'path' => $path,
                ];
            }
        }

        // Ajouter les nouvelles vidéos
        if ($request->hasFile('new_videos')) {
            foreach ($request->file('new_videos') as $video) {
                $path = $video->store('properties/videos', 'public');
                $videos[] = [
                    'filename' => basename($path),
                    'path' => $path,
                ];
            }
        }

        $property->update([
            ...$validated,
            'images' => $images,
            'videos' => $videos,
            'features' => $validated['features'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,

        ]);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Propriété mise à jour avec succès.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeAccess($property);

        // Suppression des fichiers gérée ailleurs ou dans un event/model observer
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Propriété supprimée avec succès.');
    }

    public function editMedia(Property $property)
    {
        $this->authorizeAccess($property);
        return view('properties.media', compact('property'));
    }

    private function authorizeAccess(Property $property)
    {
        if ($property->owner_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
