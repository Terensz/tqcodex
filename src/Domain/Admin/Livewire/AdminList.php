<?php

namespace Domain\Admin\Livewire;

use Domain\Admin\Models\Admin;
use Domain\Shared\Helpers\RouteHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class AdminList extends BaseListComponent
{
    /**
     * @var string
     */
    public $lastname;

    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $email;

    /**
     * @var array<mixed>
     */
    public $role = [];

    /**
     * @var array<mixed>
     */
    public $status = [];

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'admin.admin.edit';

    public $entityClassReference = 'admin';

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
        return 'admin-list';
    }

    public function getCurrentRoleType()
    {
        return UserService::ROLE_TYPE_ADMIN;
    }

    // public function mount()
    // {
    //     dump(RouteHelper::getRoutingArray());exit;
    // }

    /**
     * return
     */
    public function getBuilder(): object
    {
        $query = Admin::listableByAdmin();

        // if(UserService::getAdmin()) {
        //     $query->where('id', '!=', UserService::getAdmin()->id);
        // }

        if (! empty($this->lastname)) {
            $query->where('lastname', 'like', '%'.$this->lastname.'%');
        }

        if (! empty($this->firstname)) {
            $query->where('firstname', 'like', '%'.$this->firstname.'%');
        }

        if (! empty($this->email)) {
            $query->where('email', 'like', '%'.$this->email.'%');
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
                self::PROPERTY => Admin::PROPERTY_LAST_NAME,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Lastname',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => Admin::PROPERTY_FIRST_NAME,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Firstname',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => Admin::PROPERTY_EMAIL,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Email',
                self::TRANSLATE_CELL_DATA => false,
            ],
            // [
            //     'id' => 'role',
            //     'type' => 'select2',
            //     'translationReference' => 'Role',
            //     'options' => User::distinct()->pluck('role'),
            //     'translateCellData' => true,
            // ],
            // [
            //     'id' => 'status',
            //     'type' => 'select2',
            //     'translationReference' => 'Status',
            //     'options' => User::distinct()->pluck('status'),
            //     'translateCellData' => true,
            // ],
            [
                self::PROPERTY => 'created_at',
                self::INPUT_TYPE => 'datetimePicker',
                self::TRANSLATION_REFERENCE => 'shared.CreatedAt',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
