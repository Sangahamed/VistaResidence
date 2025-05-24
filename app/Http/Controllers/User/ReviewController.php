<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;


class ReviewController extends Controller
{
    public function store(Request $request, Property $property)
{
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:500'
    ]);
    
    $review = $property->reviews()->create([
        'user_id' => auth()->id(),
        'rating' => $validated['rating'],
        'comment' => $validated['comment']
    ]);
    
    return back()->with('success', 'Votre avis a été publié!');
}
}
