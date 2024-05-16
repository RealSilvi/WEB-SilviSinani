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
    'defaultUrlStorage'=>asset('/storage/utilities/profileDefault.jpg'),
])

<div x-cloak x-data="imagePreview({ defaultUrl:@js($defaultUrlStorage) })" class="overflow-hidden">

    <div class="w-full flex items-center justify-center pb-2">
        <img @click="$refs.imageFile.click()" :src="imageUrl" alt="Preview Uploaded Image" class="cursor-pointer aspect-[1/1] object-cover rounded-full w-1/2">
    </div>

    <input
        x-ref="imageFile" @change="previewFile"
        name="{{ $name }}"
        type="{{ $type }}"
        accept="{{ $accept }}"
        {{ $attributes->class([
            'mx-auto w-fit h-fit bg-transparent px-0 py-2 text-sm font-normal flex items-center justify-center',
            'text-gray-50 placeholder-white/70 ' => $reverse,
            'text-primary placeholder-primary' => !$reverse,
        ]) }}>

</div>
