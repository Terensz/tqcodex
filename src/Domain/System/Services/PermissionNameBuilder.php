<?php

namespace Domain\System\Services;

use Domain\User\Services\PermissionService;

class PermissionNameBuilder
{
    private ?string $domain;

    private ?string $permission;

    public function domain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    public function view()
    {
        return $this->setPermission(PermissionService::PREFIX_VIEW);
    }

    public function create()
    {
        return $this->setPermission(PermissionService::PREFIX_CREATE);
    }

    public function edit()
    {
        return $this->setPermission(PermissionService::PREFIX_EDIT);
    }

    public function delete()
    {
        return $this->setPermission(PermissionService::PREFIX_DELETE);
    }

    private function setPermission($permission)
    {
        $this->permission = $permission;

        return (string) $this;
    }

    public function __toString()
    {
        return sprintf('%s%s%s', $this->permission, PermissionService::PERMISSION_PART_SEPARATOR, $this->domain);
    }
}
