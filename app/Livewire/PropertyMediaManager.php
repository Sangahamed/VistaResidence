<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;

class PropertyMediaManager extends Component
{
    use WithFileUploads;

    public $property;
    public $newImages = [];
    public $newVideos = [];
    public $images = [];
    public $videos = [];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->images = $property->images ?? [];
        $this->videos = $property->videos ?? [];
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function updatedNewVideos()
    {
        $this->validate([
            'newVideos.*' => 'mimes:mp4,mov,avi|max:20480',
        ]);
    }

    public function saveMedia()
    {
        foreach ($this->newImages as $image) {
            $path = $image->store('properties/images', 'public');
            $this->images[] = [
                'filename' => basename($path),
                'path' => $path,
            ];
        }

        foreach ($this->newVideos as $video) {
            $path = $video->store('properties/videos', 'public');
            $this->videos[] = [
                'filename' => basename($path),
                'path' => $path,
            ];
        }

        $this->property->update([
            'images' => $this->images,
            'videos' => $this->videos,
        ]);

        $this->newImages = [];
        $this->newVideos = [];
    }

    public function removeMedia($type, $index)
    {
        if ($type === 'image') {
            Storage::delete($this->images[$index]['path']);
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        } elseif ($type === 'video') {
            Storage::delete($this->videos[$index]['path']);
            unset($this->videos[$index]);
            $this->videos = array_values($this->videos);
        }

        $this->property->update([
            'images' => $this->images,
            'videos' => $this->videos,
        ]);
    }

    public function render()
    {
        return view('livewire.property-media-manager');
    }
}
