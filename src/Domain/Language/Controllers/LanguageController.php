<?php

namespace Domain\Language\Controllers;

use Domain\Admin\Controllers\Base\BaseCrudController;
use Domain\Language\Models\Language;
use Domain\Shared\Controllers\Base\BaseContentController;

class LanguageController extends BaseCrudController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    public string $modelClass = Language::class;

    public string $domain = 'language';

    public string $titleLangKey = 'language.Singular';

    public array $defaultCreateModelParams = ['is_default' => '0'];
}
