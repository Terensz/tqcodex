<?php

namespace Domain\Language\Livewire;

use Domain\Language\Models\Language;
use Domain\Shared\Livewire\Base\BaseLivewireComponent;

final class LanguageSelector extends BaseLivewireComponent
{
    public function render()
    {
        return view('language.language_selector', [
            'systemLanguages' => Language::getSystemLanguages(),
        ]);
    }

    public static function getComponentName(): string
    {
        return 'language-selector';
    }
}
