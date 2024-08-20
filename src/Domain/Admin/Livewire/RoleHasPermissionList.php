<?php

namespace Domain\Admin\Livewire;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Models\Role;
use Domain\User\Models\RoleHasPermission;
use Domain\User\Services\UserService;

final class RoleHasPermissionList extends BaseListComponent
{
    /**
     * @var string
     */
    public $roleName;

    /**
     * @var string
     */
    public $permissionName;

    public $sortColumn = 'role_has_permissions.role_id';

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    // public $editRoute = 'admin.user.role.edit';
    public $editRoute = 'admin.user.role-has-permission.edit';

    public $entityClassReference = 'roleHasPermission';

    /**
     * @var array<mixed>
     */
    // protected $listeners = ['refreshList' => 'refreshList'];

    /**
     * @var bool
     */
    public $allowExportToExcel = true;

    public static function getComponentName(): string
    {
        return 'role-has-permission-list';
    }

    public function getCurrentRoleType()
    {
        return UserService::ROLE_TYPE_ADMIN;
    }

    /**
     * return
     */
    public function getBuilder(): object
    {
        $query = RoleHasPermission::query()
            ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id');

        if (! empty($this->roleName)) {
            $query->where('roles.name', 'like', '%'.$this->roleName.'%');
        }

        if (! empty($this->permissionName)) {
            $query->where('permissions.name', 'like', '%'.$this->permissionName.'%');
        }

        $builder = $query
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $builder;
    }

    public function getTableData(): array
    {
        return [
            [
                self::PROPERTY => 'roleName',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'role.Role',
                self::TRANSLATE_CELL_DATA => true,
            ],
            [
                self::PROPERTY => 'permissionName',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                self::TRANSLATION_REFERENCE => 'permission.Permission',
                self::TRANSLATE_CELL_DATA => true,
            ],
        ];
    }
}
