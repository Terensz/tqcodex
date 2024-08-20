<?php

namespace Domain\Customer\Controllers\Auth;

use Domain\Customer\Models\Contact;
use Domain\Customer\Requests\EmailVerificationRequest;
use Domain\Shared\Controllers\Base\BaseContentController;
use Domain\User\Services\RoleService;
use Domain\User\Services\UserRoleService;
use Domain\User\Services\UserService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends BaseContentController
{
    public static function getContentBranch(): string
    {
        return BaseContentController::CONTENT_BRANCH_PUBLIC_AREA;
    }

    /**
     * route: customer.verification.verify
     * url: /verify-email/{id}/{hash}
     */
    public function __invoke(EmailVerificationRequest $request, $id, $hash): RedirectResponse
    {
        $contact = Contact::find($id);

        if ($contact && $contact instanceof Contact && $contact->getContactProfile() && sha1($contact->getContactProfile()->email) === $hash) {
            if ($contact->getContactProfile()->hasVerifiedEmail()) {
                return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
            }

            if ($contact->getContactProfile()->markEmailAsVerified()) {
                UserRoleService::syncRolesToCustomer([RoleService::ROLE_REGISTERED_CUSTOMER], $contact);

                /** @phpstan-ignore-next-line */
                event(new Verified($contact->getContactProfile()));
            }
        }

        return redirect()->intended(UserService::getDashboardRoute(UserService::ROLE_TYPE_CUSTOMER).'?verified=1');
    }
}
