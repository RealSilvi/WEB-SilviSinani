<div x-data="{ toast: {{ session('toast') ? json_encode(session('toast'), 1) : 'null' }}, show: false }" x-on:toast.window="toast = $event.detail; show = true; setTimeout(() => show = false, 3000)"
    x-init="$nextTick(() => {
        if (toast) {
            show = true;
            setTimeout(() => show = false, 3000)
        }
    })">
    <div class="lg:mr-21 fixed right-0 top-0 z-50 mr-10 mt-24 max-w-[80vw] transform transition duration-300 ease-in-out lg:mt-40"
        x-show="show" x-transition:enter-start="translate-x-full opacity-0"
        x-transition:leave-end="translate-x-full opacity-0">
        <template x-if="toast">
            <div class="rounded px-4 py-2 text-white shadow-lg" x-text="toast.message"
                :class="{
                    'bg-success-dark': toast.type === 'success',
                    'bg-error-dark': toast.type === 'error',
                    'bg-info-dark': toast.type === 'info' || toast.type === null
                }">
            </div>
        </template>
    </div>
</div>
