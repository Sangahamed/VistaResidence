@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Lead: {{ $lead->name }}</h1>
        <a href="{{ route('leads.show', $lead->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Lead
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Contact Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs italic mt
