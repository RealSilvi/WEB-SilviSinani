@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'text',
    'name' => '',
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
])

<input name="{{ $name }}" type="{{ $type }}"
    {{ $attributes->class([
        'w-full bg-transparent px-0 py-2 text-base font-normal border-0 border-b focus:ring-0 focus:outline-0 focus:outline-offset-0',
        'text-gray-50 placeholder-white/70 border-white/50 focus:border-white focus:outline-white' => $reverse,
        'text-primary placeholder-primary border-primary focus:border-primary' => !$reverse,
    ]) }}>
