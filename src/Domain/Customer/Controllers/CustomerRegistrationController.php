<?php

namespace Domain\Customer\Controllers;

use Domain\Customer\Controllers\Base\BaseCustomerController;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\Shared\Helpers\Crypter;
use Illuminate\Http\Request;

class CustomerRegistrationController extends BaseCustomerController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['validate.customer', 'redirect.non.admin']);
    }

    /**
     * register
     */
    public function register(Request $request)
    {
        return $this->renderContent($request, 'customer.contact.register', __('customer.ContactRegister'), [
            'invitedRegister' => false,
            'partnerEmail' => null,
            'partnerName' => null,
            'partnerContact' => null,
        ]);
    }

    /**
     * Invited register
     */
    public function invitedRegister(Request $request, $partnerEmail, $partnerName, $partnerContact)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $partnerEmail = Crypter::decrypt($partnerEmail);
        $partnerName = Crypter::decrypt($partnerName);
        $partnerContact = Crypter::decrypt($partnerContact);

        return $this->renderContent($request, 'customer.contact.register', __('customer.ContactRegister'), [
            'invitedRegister' => true,
            'partnerEmail' => $partnerEmail,
            'partnerName' => $partnerName,
            'partnerContact' => $partnerContact,
        ]);
    }
}
