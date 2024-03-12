<div>
    <ul class="flex flex-col justify-around gap-6">
        @foreach ($menu as $item)
            <li>
                <a href="{{ $item['url'] }}" class="text-base">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
