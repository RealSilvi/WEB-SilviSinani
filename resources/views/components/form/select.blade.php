@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp

@aware(['error', 'name', 'reverse'])

@props([
    'type' => 'text',
    'name' => '',
    'placeholder' => '',
    'error' => isset($name) && isset($errors) ? $errors->first($name) : null,
    'reverse' => false,
])

<select x-bind:id="$id('input')" name="{{ $name }}"
    {{ $attributes->class([
        'w-full bg-transparent px-0 py-2 font-normal text-sm border-0 border-b focus:ring-0 focus:outline-0 focus:outline-offset-0',
        'text-white placeholder-white border-white focus:border-white focus:outline-white' => $reverse,
        'text-black placeholder-gray-700 border-black focus:border-black focus:outline-black' => !$reverse,
    ]) }}>
    <option value="">{{ $placeholder }}</option>
    {{ $slot }}
</select>
