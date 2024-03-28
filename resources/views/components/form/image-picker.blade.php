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
    'defaultUrlStorage'=>asset('/storage/utilities/image-placeholder.png'),
])

<div x-cloak x-data="imagePreview({ defaultUrl:@js($defaultUrlStorage) })">
    <div @click="$refs.imageFile.click()" class="flex items-center justify-center">
        <img :src="imageUrl" alt="Preview Uploaded Image" class="aspect-[1/1] object-contain rounded-full w-1/2">
    </div>
    <input
        x-ref="imageFile" @change="previewFile"
        name="{{ $name }}"
        type="{{ $type }}"
        accept="{{ $accept }}"
        {{ $attributes->class([
            'w-full h-fit bg-transparent px-0 py-2 text-base font-normal',
            'text-gray-50 placeholder-white/70 ' => $reverse,
            'text-primary placeholder-primary' => !$reverse,
        ]) }}>

</div>
