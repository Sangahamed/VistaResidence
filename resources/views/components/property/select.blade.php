@props(['name', 'label', 'options' => [], 'selected' => null, 'required' => false, 'class' => 'col-12'])
<div class="mb-3 {{ $class }}">
    <label for="{{ $name }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-select @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}>
        <option value="">-- Sélectionner --</option>
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ old($name, $selected) == $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>