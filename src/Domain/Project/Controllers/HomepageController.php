<?php

namespace Domain\Project\Controllers;

use Domain\Shared\Controllers\Base\BaseContentController;
use Illuminate\Http\Request;

class HomepageController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_GROUP_PUBLIC_AREA;
    }

    /**
     * index
     */
    public function index(Request $request)
    {
        return $this->renderPage($request, 'public.page-switcher.page-livewire-loader', __('shared.Homepage'), [
            
        ]);
    }
}
