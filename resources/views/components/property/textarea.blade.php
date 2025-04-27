@props(['name', 'label', 'value' => '', 'required' => false])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    <textarea name="{{ $name }}" id="{{ $name }}" rows="4" class="form-control @error($name) is-invalid @enderror">{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>