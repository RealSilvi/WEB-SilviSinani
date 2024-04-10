@php
    /**
     * @var array{label: string, url: string} $menu
     */
@endphp
<div>
    <ul class="flex flex-col justify-around gap-6">
        @foreach ($menu as $item)
            <li>
                <a href="{{ $item['url'] }}" class="text-base">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button>{{ __('Logout') }}</button>
            </form>
        </li>
    </ul>
</div>
