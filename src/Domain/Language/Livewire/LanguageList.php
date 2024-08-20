<?php

namespace Domain\Language\Livewire;

use Domain\Language\Models\Language;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Illuminate\Database\Eloquent\Builder;

final class LanguageList extends BaseListComponent
{
    public string $name;

    public string $locale;

    /**
     * return
     */
    public function getBuilder(): object
    {
        $builder = Language::query();

        collect(['name', 'locale'])->each(function ($item) use ($builder) {
            $builder->when(! empty($this->{$item}), fn (Builder $builder) => $builder->where($item, 'like', sprintf('%%%s%%', $this->{$item}))
            );
        });

        $builder->orderBy('is_default', 'DESC');

        return $builder;
    }

    public function getTableData(): array
    {
        return [
            [
                self::PROPERTY => 'name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'language.Name',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'locale',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'language.Locale',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'isDefaultReadable',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                self::TRANSLATION_REFERENCE => 'language.Default',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
