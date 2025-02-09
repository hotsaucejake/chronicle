<x-filament::dropdown
    placement="bottom-end"
    teleport=""
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-user-menu'])
    "
>
    <x-slot name="trigger">
        <button
            aria-label="Settings"
            type="button"
            class="shrink-0"
        >
            <x-filament::icon icon="heroicon-s-cog-6-tooth" class="h-6 w-6" />
        </button>
    </x-slot>

    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
        <x-filament::dropdown.list>
            <x-filament-panels::theme-switcher />
        </x-filament::dropdown.list>
    @endif

</x-filament::dropdown>
