@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'step' => null, 'class' => 'col-12', 'icon' => null])
<div class="mb-3 {{ $class }}">
    <label for="{{ $name }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    <div class="input-group">
        <input type="{{ $type }}" 
               name="{{ $name }}" 
               id="{{ $name }}" 
               value="{{ old($name, $value) }}" 
               {{ $required ? 'required' : '' }}
               step="{{ $step }}"
               class="form-control @error($name) is-invalid @enderror">
        @if($icon)
            <span class="input-group-text">{{ $icon }}</span>
        @endif
    </div>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>