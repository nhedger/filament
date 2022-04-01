@php
    $sideLabelClasses = [
        'whitespace-nowrap group-focus-within:text-primary-500',
        'text-gray-400' => ! $errors->has($getStatePath()),
        'text-danger-400' => $errors->has($getStatePath()),
    ];
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class(['flex items-center space-x-1 group filament-forms-select-component']) }}>
        @if ($label = $getPrefixLabel())
            <span @class($sideLabelClasses)>
                {{ $label }}
            </span>
        @endif

        <div class="flex-1 min-w-0">
            @unless ($isSearchable() || $isMultiple())
                <select
                    {!! $isAutofocused() ? 'autofocus' : null !!}
                    {!! $isDisabled() ? 'disabled' : null !!}
                    id="{{ $getId() }}"
                    {!! $isRequired() ? 'required' : null !!}
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                    dusk="filament.forms.{{ $getStatePath() }}"
                    {{ $attributes->merge($getExtraInputAttributes())->merge($getExtraAttributes())->class([
                        'text-gray-900 block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                        'dark:bg-gray-700 dark:text-white' => config('forms.dark_mode'),
                        'border-gray-300' => ! $errors->has($getStatePath()),
                        'dark:border-gray-600' => (! $errors->has($getStatePath())) && config('forms.dark_mode'),
                        'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                    ]) }}
                >
                    @unless ($isPlaceholderSelectionDisabled())
                        <option value="">{{ $getPlaceholder() }}</option>
                    @endif

                    @foreach ($getOptions() as $value => $label)
                        <option
                            value="{{ $value }}"
                            {!! $isOptionDisabled($value, $label) ? 'disabled' : null !!}
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            @else
                <div
                    x-data="selectFormComponent({
                        getOptionLabelUsing: async (value) => {
                            return await $wire.getSelectOptionLabel(@js($getStatePath()))
                        },
                        getOptionsUsing: async () => {
                            return await $wire.getSelectOptions(@js($getStatePath()))
                        },
                        getSearchResultsUsing: async (searchQuery) => {
                            return await $wire.getSelectSearchResults(@js($getStatePath()), searchQuery)
                        },
                        isAutofocused: @js($isAutofocused()),
                        isMultiple: @js($isMultiple()),
                        hasDynamicOptions: @js($hasDynamicOptions()),
                        maxItems: @js($getMaxItems()),
                        noSearchResultsMessage: @js($getNoSearchResultsMessage()),
                        options: @js($getOptions()),
                        placeholder: @js($getPlaceholder()),
                        state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
                    })"
                    wire:ignore
                    {{ $attributes->merge($getExtraAttributes())->merge($getExtraAlpineAttributes()) }}
                >
                    <select
                        x-ref="input"
                        id="{{ $getId() }}"
                        {!! $isDisabled() ? 'disabled' : null !!}
                        {!! $isMultiple() ? 'multiple' : null !!}
                        {!! $isRequired() ? 'required' : null !!}
                        {{ $getExtraInputAttributeBag() }}
                    ></select>
                </div>
            @endif
        </div>

        @if($hasCreateFormSchema())
            <div>
                <x-forms::icon-button wire:click="mountFormComponentAction('{{ $getStatePath() }}', 'create')" icon="heroicon-o-plus" class="-mr-2" />
            </div>
        @endif

        @if ($label = $getPostfixLabel())
            <span @class($sideLabelClasses)>
                {{ $label }}
            </span>
        @endif
    </div>
</x-dynamic-component>
