@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'file',
    'name' => '',
    'accept'=>".jpg, .jpeg, .png, .svg",
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
])

<input
    name="{{ $name }}"
    type="{{ $type }}"
    accept="{{ $accept }}"
    {{ $attributes->class([
        'w-fit h-fit bg-transparent px-0 py-2 text-sm font-normal flex items-center justify-center',
        'text-gray-50 placeholder-white/70 ' => $reverse,
        'text-primary placeholder-primary' => !$reverse,
    ]) }}>

