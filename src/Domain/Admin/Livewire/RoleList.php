<?php

namespace Domain\Admin\Livewire;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Models\Role;
use Domain\User\Services\UserService;

final class RoleList extends BaseListComponent
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $guard_name;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'admin.user.role.edit';

    public $entityClassReference = 'role';

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
        return 'role-list';
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
        $query = Role::valid();

        if (! empty($this->name)) {
            $query->where('name', 'like', '%'.$this->name.'%');
        }

        if (! empty($this->guard_name)) {
            $query->where('guard_name', 'like', '%'.$this->guard_name.'%');
        }

        // if (! empty($this->role)) {
        //     $query->whereIn('role', $this->role);
        // }

        // if (! empty($this->status)) {
        //     $query->whereIn('status', $this->status);
        // }

        $builder = $query
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $builder;
    }

    public function getTableData(): array
    {
        return [
            [
                self::PROPERTY => 'name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'role.Name',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'guard_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SIMPLE_SELECT,
                self::TRANSLATION_REFERENCE => 'role.GuardName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'created_at',
                self::INPUT_TYPE => 'datetimePicker',
                self::TRANSLATION_REFERENCE => 'shared.CreatedAt',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
