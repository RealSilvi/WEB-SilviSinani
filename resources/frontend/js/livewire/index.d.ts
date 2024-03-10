export type AlpinePluginInit = (alpine: Alpine) => void;

interface Alpine {
    start(): void;

    data<T, K = any>(name: string, callback: (data: ThisTypedAlpineComponent<T>) => ThisTypedAlpineComponent<K>): void;

    store<T extends Record<string, unknown>>(value: T): void;

    plugin(callback: AlpinePluginInit): void;
}

export interface AlpineComponent {
    $el: HTMLElement;
    $root: HTMLElement;
    $refs: Record<string, HTMLElement>;
    $watch: WatchFunction;
    $store: Record<string, unknown>;
    $dispatch: DispatchFunction;
    $nextTick: NextTickFunction;
    $id: IdFunction;
}

export type ThisTypedAlpineComponent<T> = object & T & ThisType<AlpineComponent & T>;

export type DispatchFunction = <T extends any>(event: string, data: T) => void;

export type WatchFunction = <T extends any>(prop: string, callback: (value: T, oldValue: T) => any) => void;

export type NextTickFunction = (callback: () => any) => void;

export type IdFunction = (prefix: string | number, suffix?: string | number) => string;

export const Alpine: Alpine;

interface Livewire {
    start(): void;
}

export const Livewire: Livewire;
