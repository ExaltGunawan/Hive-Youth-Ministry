@php
    $offColor = $getOffColor() ?? 'gray';
    $onColor = $getOnColor() ?? 'primary';
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    @capture($content)
        <button
            wire:key="{{ $this->getId() }}"
             x-data="{
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                toggleAll: function () {
                    // Tentukan target status berdasarkan kebalikan status saat ini
                    let targetState = !this.state;
                    this.state = targetState;
                    
                    // Update semua CheckboxList (Resource)
                    document.querySelectorAll('.fi-fo-checkbox-list').forEach(list => {
                        let data = Alpine.$data(list);
                        if (data && data.options) {
                            data.state = targetState ? Object.keys(data.options).map(key => key.toString()) : [];
                        }
                    });

                    // Update semua Checkbox Satuan (Pages & Widgets)
                    document.querySelectorAll('.fi-fo-checkbox-list-option-label input[type=\'checkbox\']').forEach(checkbox => {
                        checkbox.checked = targetState;
                        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                }
            }"
            x-on:click="toggleAll();"
            x-bind:class="
                state
                    ? '{{
                        match ($onColor) {
                            'gray' => 'fi-color-gray bg-gray-200 dark:bg-gray-700',
                            default => 'fi-color-custom bg-custom-600',
                        }
                    }}'
                    : '{{
                        match ($offColor) {
                            'gray' => 'fi-color-gray bg-gray-200 dark:bg-gray-700',
                            default => 'fi-color-custom bg-custom-600',
                        }
                    }}'
            "
            x-bind:style="
                state
                    ? '{{
                        \Filament\Support\get_color_css_variables(
                            $onColor,
                            shades: [600],
                            alias: 'forms::components.toggle.on',
                        )
                    }}'
                    : '{{
                        \Filament\Support\get_color_css_variables(
                            $offColor,
                            shades: [600],
                            alias: 'forms::components.toggle.off',
                        )
                    }}'
            "
            {{
                $attributes
                    ->merge([
                        'aria-checked' => 'false',
                        'autofocus' => $isAutofocused(),
                        'disabled' => $isDisabled(),
                        'id' => $getId(),
                        'role' => 'switch',
                        'type' => 'button',
                        'wire:loading.attr' => 'disabled',
                        'wire:target' => $statePath,
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->merge($getExtraAlpineAttributes(), escape: false)
                    ->class(['fi-fo-toggle relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent outline-none transition-colors duration-200 ease-in-out disabled:pointer-events-none disabled:opacity-70'])
            }}
        >
            <span
                class="relative inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full shadow pointer-events-none ring-0"
                x-bind:class="{
                    'translate-x-5 rtl:-translate-x-5': state,
                    'translate-x-0': ! state,
                }"
            >
                <span
                    class="absolute inset-0 flex items-center justify-center w-full h-full transition-opacity"
                    aria-hidden="true"
                    x-bind:class="{
                        'opacity-0 ease-out duration-100': state,
                        'opacity-100 ease-in duration-200': ! state,
                    }"
                >
                    @if ($hasOffIcon())
                        <x-filament::icon
                            :icon="$getOffIcon()"
                            @class([
                                'fi-fo-toggle-off-icon h-3 w-3',
                                match ($offColor) {
                                    'gray' => 'text-gray-400 dark:text-gray-700',
                                    default => 'text-custom-600',
                                },
                            ])
                        />
                    @endif
                </span>

                <span
                    class="absolute inset-0 flex items-center justify-center w-full h-full transition-opacity"
                    aria-hidden="true"
                    x-bind:class="{
                        'opacity-100 ease-in duration-200': state,
                        'opacity-0 ease-out duration-100': ! state,
                    }"
                >
                    @if ($hasOnIcon())
                        <x-filament::icon
                            :icon="$getOnIcon()"
                            x-cloak="x-cloak"
                            @class([
                                'fi-fo-toggle-on-icon h-3 w-3',
                                match ($onColor) {
                                    'gray' => 'text-gray-400 dark:text-gray-700',
                                    default => 'text-custom-600',
                                },
                            ])
                        />
                    @endif
                </span>
            </span>
        </button>
    @endcapture

    @if ($isInline())
        <x-slot name="labelPrefix">
            {{ $content() }}
        </x-slot>
    @else
        {{ $content() }}
    @endif
</x-dynamic-component>
