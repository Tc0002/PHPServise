@props(['name', 'label', 'type' => 'text', 'required' => false, 'placeholder' => '', 'value' => '', 'options' => []])

<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($type === 'select')
        <select id="{{ $name }}" 
                name="{{ $name }}" 
                class="form-control @error($name) is-invalid @enderror" 
                {{ $required ? 'required' : '' }}>
            <option value="">選択してください</option>
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea id="{{ $name }}" 
                  name="{{ $name }}" 
                  class="form-control @error($name) is-invalid @enderror" 
                  rows="4" 
                  placeholder="{{ $placeholder }}" 
                  {{ $required ? 'required' : '' }}>{{ $value }}</textarea>
    @else
        <input type="{{ $type }}" 
               id="{{ $name }}" 
               name="{{ $name }}" 
               value="{{ $value }}" 
               class="form-control @error($name) is-invalid @enderror" 
               placeholder="{{ $placeholder }}" 
               {{ $required ? 'required' : '' }}>
    @endif
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div> 