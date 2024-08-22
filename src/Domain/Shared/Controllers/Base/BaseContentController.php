<?php

namespace Domain\Shared\Controllers\Base;

use Domain\Shared\Helpers\PHPHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class BaseContentController extends Controller
{
    public const CONTENT_BRANCH_PUBLIC_AREA = 'PublicArea';

    // Admin

    public const CONTENT_BRANCH_BASIC_ADMIN_INTERFACE = 'BasicAdminInterface';

    public const CONTENT_BRANCH_PROJECT_ADMIN_INTERFACE = 'ProjectAdminInterface';

    public const CONTENT_BRANCH_WEBSHOP_ADMIN_INTERFACE = 'WebshopAdminInterface';

    // Customer

    public const CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE = 'BasicCustomerInterface';

    public const CONTENT_BRANCH_ORGANIZATIONS_CUSTOMER_INTERFACE = 'OrganizationsCustomerInterface';

    public const CONTENT_BRANCH_COMMUNICATION_CUSTOMER_INTERFACE = 'CommunicationCustomerInterface';

    public const CONTENT_BRANCH_PROJECT_CUSTOMER_INTERFACE = 'ProjectCustomerInterface';

    // Main

    public const CONTENT_GROUP_PUBLIC_AREA = 'PublicAreaGroup';

    public const CONTENT_GROUP_ADMIN_INTERFACE = 'AdminInterfaceGroup';

    public const CONTENT_GROUP_CUSTOMER_INTERFACE = 'CustomerInterfaceGroup';

    public const CONTENT_GROUPS = [
        self::CONTENT_GROUP_PUBLIC_AREA => [
            self::CONTENT_BRANCH_PUBLIC_AREA,
        ],
        self::CONTENT_GROUP_ADMIN_INTERFACE => [
            self::CONTENT_BRANCH_BASIC_ADMIN_INTERFACE,
            self::CONTENT_BRANCH_PROJECT_ADMIN_INTERFACE,
            self::CONTENT_BRANCH_WEBSHOP_ADMIN_INTERFACE,
        ],
        self::CONTENT_GROUP_CUSTOMER_INTERFACE => [
            self::CONTENT_BRANCH_BASIC_CUSTOMER_INTERFACE,
            self::CONTENT_BRANCH_ORGANIZATIONS_CUSTOMER_INTERFACE,
            self::CONTENT_BRANCH_COMMUNICATION_CUSTOMER_INTERFACE,
            self::CONTENT_BRANCH_PROJECT_CUSTOMER_INTERFACE,
        ],
    ];

    public static function getContentBranch(): string
    {
        return '';
    }

    public static function getContentGroup(): ?string
    {
        $contentBranch = static::getContentBranch();

        if (empty($contentBranch)) {
            return null;
        }

        foreach (self::CONTENT_GROUPS as $contentGroup => $contentBranches) {
            if (in_array($contentBranch, $contentBranches)) {
                return $contentGroup;
            }
        }

        return null;
    }

    public function renderContent(Request $request, string $view, $title, $params = [])
    {
        $params['title'] = $title;

        $toasts = [];
        if (isset($params['toasts']) && is_array($params['toasts'])) {
            $toasts = $params['toasts'];
        }
        $toasts = PHPHelper::arrayMerge($toasts, $this->collectToastsFromSession($request));
        $params['toasts'] = $toasts;
        $params['contentBranch'] = static::getContentBranch();

        return view($view, $params);
    }

    public function renderPage(Request $request, string $title, $params = [])
    {
        return $this->renderContent($request, 'public.page-switcher.page-livewire-loader', $title, $params);
    }

    public function collectToastsFromSession(Request $request)
    {
        $toasts = [];
        $status = $request->session()->get('status');
        $success = $request->session()->get('success');
        $error = $request->session()->get('error');

        if ($status) {
            $toasts[] = [
                'type' => 'success',
                'message' => $status === 'profile-updated' ? trans('user.ProfileUpdated') : $status,
            ];
        }
        if ($success) {
            $toasts[] = [
                'type' => 'success',
                'message' => $success,
            ];
        }
        if ($error) {
            $toasts[] = [
                'type' => 'error',
                'message' => $error,
            ];
        }

        return $toasts;
    }

    // public function fireRendringRouteContentEvent(Request $request)
    // {
    //     RendringRouteContent::dispatch($request);
    // }

    // public function renderAjaxResponse(string $view, $viewParams = [], $data = [])
    // {
    //     if (request()->ajax() || request()->wantsJson()) {
    //         return response()->json([
    //             'view' => view($view, $viewParams)->render(),
    //             'data' => $data,
    //         ]);
    //     }

    //     return view($view, $viewParams);
    // }
}
