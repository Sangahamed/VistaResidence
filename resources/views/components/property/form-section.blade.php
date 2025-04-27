@props(['title'])
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>