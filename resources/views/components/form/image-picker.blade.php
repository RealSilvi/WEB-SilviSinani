@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'file',
    'name' => '',
    'accept'=>".jpg, .jpeg, .png",
    'error' => isset($name) && $name !== null ? $errors->first($name) : null,
    'reverse' => false,
])

<div >
    <img src="https://source.unsplash.com/random" alt="Preview Uploaded Image" class="h-10"  >
    <input
        name="{{ $name }}"
        type="{{ $type }}"
        accept="{{ $accept }}"
        {{ $attributes->class([
            'w-full bg-transparent px-0 py-2 text-base font-normal focus:ring-0 focus:outline-0 focus:outline-offset-0',
            'text-gray-50 placeholder-white/70 border-white/50 focus:border-white focus:outline-white' => $reverse,
            'text-primary placeholder-primary border-primary focus:border-primary' => !$reverse,
        ]) }}>

</div>
