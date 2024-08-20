<?php

namespace Domain\Admin\Builders;

use Domain\Admin\Enums\AdminStatus;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\User\Services\UserRoleService;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends BaseBuilder
{
    /**
     * Admin can list all Customers, regardless of anything.
     */
    public function listableByAdmin()
    {
        $allowedRoleNamesToView = UserRoleService::getAllowedAdminRoleNames();

        return $this->where(function (Builder $query) use ($allowedRoleNamesToView) {
            $query->whereHas('roles', function (Builder $query) use ($allowedRoleNamesToView) {
                $query->whereIn('name', $allowedRoleNamesToView);
            })->orWhereDoesntHave('roles');
        });
    }

    /**
     * Customers can only "list" their own Contact.
     */
    public function listableByCustomer()
    {
        return $this;
    }

    public function valid()
    {
        return $this
            ->verified()
            ->active();
    }

    public function verified()
    {
        return $this->whereNotNull('email_verified_at');
    }

    public function active()
    {
        return $this->where('status', AdminStatus::ACTIVE->value);
    }

    public function registeredLastMonth()
    {
        return $this->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ]);
    }
}
