
            <div class="flex justify-between">
                <div class="flex items-center gap-4">
                    <x-primary-button wire:click.prevent="save" name="save" id="save">{{ __('shared.Save') }}</x-primary-button>
                </div>
                @if(isset($middleButton) && $middleButton['active'])
                <div class="flex items-center gap-4">
                    <x-secondary-button wire:click.prevent="{{ $middleButton['backendMethod'] }}" target="_blank">{{ $middleButton['label'] }}</x-secondary-button>
                </div>
                @endif
                <div class="flex items-center gap-4">
                    <x-delete-button wire:confirm="{{ __('shared.ConfirmDeleteMessage') }}" wire:click.prevent="delete">{{ __('shared.Delete') }}</x-delete-button>
                </div>
            </div>
