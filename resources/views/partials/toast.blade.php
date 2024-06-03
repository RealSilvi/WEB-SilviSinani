<div x-data="{ toast: {{ session('toast') ? json_encode(session('toast'), 1) : 'null' }}, show: false }" x-on:toast.window="toast = $event.detail; show = true; setTimeout(() => show = false, 3000)"
    x-init="$nextTick(() => {
        if (toast) {
            show = true;
            setTimeout(() => show = false, 3000)
        }
    })">
    <div class="fixed right-0 top-20 z-50 mr-2 max-w-[80vw] transform transition duration-300 ease-in-out lg:mr-20"
        x-show="show" x-transition:enter-start="translate-x-full opacity-0"
        x-transition:leave-end="translate-x-full opacity-0">
        <template x-if="toast">
            <div class="rounded px-4 py-2 text-white shadow-lg" x-text="toast.message"
                :class="{
                    'bg-primary/75': toast.type === 'success',
                    'bg-red-700': toast.type === 'error'
                }">
            </div>
        </template>
    </div>
</div>
