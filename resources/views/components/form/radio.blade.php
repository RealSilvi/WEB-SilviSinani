@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'radio',
    'name' => '',
    'value' => '',
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
])

<input name="{{ $name }}"
       type="{{ $type }}"
       value="{{ $value }}"
    {{ $attributes->class([]) }}>
