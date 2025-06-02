<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PropertyMediaManager extends Component
{
    use WithFileUploads;

    public $property;
    public $newImages = [];
    public $newVideos = [];
    public $newPanoramicImages = [];
    public $images = [];
    public $videos = [];
    public $panoramicImages = [];
    public $activeTab = 'images';
    public $uploadInProgress = false;
    
    // Virtual tour properties
    public $virtualTourType = null;
    public $virtualTourUrl = '';
    public $hasVirtualTour = false;

    protected $listeners = ['mediaUploaded' => 'handleMediaUploaded'];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->images = $property->images ?? [];
        $this->videos = $property->videos ?? [];
        $this->panoramicImages = $property->panoramic_images ?? [];
        $this->virtualTourType = $property->virtual_tour_type;
        $this->virtualTourUrl = $property->virtual_tour_url ?? '';
        $this->hasVirtualTour = $property->has_virtual_tour ?? false;
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);
        
        $this->saveMedia('images');
    }

    public function updatedNewVideos()
    {
        $this->validate([
            'newVideos.*' => 'mimes:mp4,mov,avi,webm|max:51200', // 50MB
        ]);
        
        $this->saveMedia('videos');
    }

    public function updatedNewPanoramicImages()
    {
        $this->validate([
            'newPanoramicImages.*' => 'image|mimes:jpeg,png,jpg|max:10240', // 10MB for panoramic
        ]);
        
        $this->saveMedia('panoramic');
    }

    public function saveMedia($type = null)
    {
        $this->uploadInProgress = true;
        
        try {
            if (!$type || $type === 'images') {
                foreach ($this->newImages as $image) {
                    $path = $image->store('properties/images', 'public');
                    $this->images[] = [
                        'filename' => basename($path),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                    ];
                }
                $this->newImages = [];
            }

            if (!$type || $type === 'videos') {
                foreach ($this->newVideos as $video) {
                    $path = $video->store('properties/videos', 'public');
                    $this->videos[] = [
                        'filename' => basename($path),
                        'path' => $path,
                        'size' => $video->getSize(),
                        'mime_type' => $video->getMimeType(),
                    ];
                }
                $this->newVideos = [];
            }

            if (!$type || $type === 'panoramic') {
                foreach ($this->newPanoramicImages as $image) {
                    $path = $image->store('properties/panoramic', 'public');
                    $this->panoramicImages[] = [
                        'filename' => basename($path),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                    ];
                }
                $this->newPanoramicImages = [];
            }

            $this->property->update([
                'images' => $this->images,
                'videos' => $this->videos,
                'panoramic_images' => $this->panoramicImages,
            ]);

            $this->dispatch('mediaUploaded');
            session()->flash('message', 'Médias uploadés avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload des médias: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de l\'upload des médias.');
        } finally {
            $this->uploadInProgress = false;
        }
    }

    public function removeMedia($type, $index)
    {
        try {
            if ($type === 'image' && isset($this->images[$index])) {
                Storage::disk('public')->delete($this->images[$index]['path']);
                unset($this->images[$index]);
                $this->images = array_values($this->images);
            } elseif ($type === 'video' && isset($this->videos[$index])) {
                Storage::disk('public')->delete($this->videos[$index]['path']);
                unset($this->videos[$index]);
                $this->videos = array_values($this->videos);
            } elseif ($type === 'panoramic' && isset($this->panoramicImages[$index])) {
                Storage::disk('public')->delete($this->panoramicImages[$index]['path']);
                unset($this->panoramicImages[$index]);
                $this->panoramicImages = array_values($this->panoramicImages);
            }

            $this->property->update([
                'images' => $this->images,
                'videos' => $this->videos,
                'panoramic_images' => $this->panoramicImages,
            ]);

            session()->flash('message', 'Média supprimé avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du média: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la suppression du média.');
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function createBasicVirtualTour()
    {
        if (empty($this->images)) {
            session()->flash('error', 'Vous devez d\'abord ajouter des images pour créer une visite virtuelle basique.');
            return;
        }

        $this->virtualTourType = 'basic';
        $this->hasVirtualTour = true;
        $this->saveVirtualTour();
    }

    public function createPanoramicVirtualTour()
    {
        if (empty($this->panoramicImages)) {
            session()->flash('error', 'Vous devez d\'abord ajouter des images panoramiques.');
            return;
        }

        $this->virtualTourType = 'panoramic';
        $this->hasVirtualTour = true;
        $this->saveVirtualTour();
    }

    public function create3DVirtualTour()
    {
        $this->validate([
            'virtualTourUrl' => 'required|url',
        ]);

        $this->virtualTourType = '3d';
        $this->hasVirtualTour = true;
        $this->saveVirtualTour();
    }

    public function saveVirtualTour()
    {
        try {
            $this->property->update([
                'virtual_tour_type' => $this->virtualTourType,
                'virtual_tour_url' => $this->virtualTourUrl,
                'has_virtual_tour' => $this->hasVirtualTour,
            ]);

            session()->flash('message', 'Visite virtuelle créée avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la visite virtuelle: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la création de la visite virtuelle.');
        }
    }

    public function deleteVirtualTour()
    {
        try {
            $this->property->update([
                'virtual_tour_type' => null,
                'virtual_tour_url' => null,
                'has_virtual_tour' => false,
            ]);

            $this->virtualTourType = null;
            $this->virtualTourUrl = '';
            $this->hasVirtualTour = false;

            session()->flash('message', 'Visite virtuelle supprimée avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la visite virtuelle: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la suppression de la visite virtuelle.');
        }
    }

    public function handleMediaUploaded()
    {
        $this->property->refresh();
        $this->images = $this->property->images ?? [];
        $this->videos = $this->property->videos ?? [];
        $this->panoramicImages = $this->property->panoramic_images ?? [];
    }

    public function render()
    {
        return view('livewire.property-media-manager');
    }
}
