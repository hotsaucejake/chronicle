<x-filament-panels::page
    @class([
        'fi-resource-edit-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->uuid,
    ])
>
    <div
        x-data="{
            editingUsers: {},
            init() {
                Echo.private(`spatie_document.{{ $record->uuid }}`)
                    .listen('.App\\Events\\Document\\SpatieDocumentEditedBroadcast', (e) => {
                        $wire.set('data.content', e.new_content);
                    })
                    .listen('.App\\Events\\Document\\SpatieDocumentEditingBroadcast', (e) => {
                        this.editingUsers[e.username] = Date.now();
                    });

                setInterval(() => {
                     const now = Date.now();
                     for (let user in this.editingUsers) {
                         if (now - this.editingUsers[user] > 10000) {
                             delete this.editingUsers[user];
                         }
                     }
                 }, 6000);

                $wire.call('pingEditing');
                setInterval(() => { $wire.call('pingEditing'); }, 6000);
            }
        }">
        @capture($form)
        <x-filament-panels::form
            id="form"
            :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
            wire:submit="save"
            class="mb-6"
        >
            {{ $this->form }}

            <!-- Display an indicator if any other users are editing -->
            <div class="mt-4 text-sm text-gray-500" x-show="Object.keys(editingUsers).length > 0">
                <span x-text="Object.keys(editingUsers).join(', ') + ' is currently editing'"></span>
                <span class="animate-bounce inline-block ml-1">...</span>
            </div>

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
        @endcapture

        @php
            $relationManagers = $this->getRelationManagers();
            $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent();
        @endphp

        @if ((! $hasCombinedRelationManagerTabsWithContent) || (! count($relationManagers)))
            {{ $form() }}
        @endif

        @if (count($relationManagers))
            <x-filament-panels::resources.relation-managers
                :active-locale="isset($activeLocale) ? $activeLocale : null"
                :active-manager="$this->activeRelationManager ?? ($hasCombinedRelationManagerTabsWithContent ? null : array_key_first($relationManagers))"
                :content-tab-label="$this->getContentTabLabel()"
                :content-tab-icon="$this->getContentTabIcon()"
                :content-tab-position="$this->getContentTabPosition()"
                :managers="$relationManagers"
                :owner-record="$record"
                :page-class="static::class"
            >
                @if ($hasCombinedRelationManagerTabsWithContent)
                    <x-slot name="content">
                        {{ $form() }}
                    </x-slot>
                @endif
            </x-filament-panels::resources.relation-managers>
        @endif

        <x-filament-panels::page.unsaved-data-changes-alert />
    </div>
</x-filament-panels::page>
