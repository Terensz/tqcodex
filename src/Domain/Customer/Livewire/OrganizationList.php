<?php

namespace Domain\Customer\Livewire;

use Domain\Customer\Enums\Location;
use Domain\Customer\Models\ContactOrg;
// use Domain\Customer\Models\Organization;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class OrganizationList extends BaseListComponent
{
    /**
     * @var string
     */
    public $is_banned;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $long_name;

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $city;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'customer.organization.edit';

    public $entityClassReference = 'organization';

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
        return 'organization-list';
    }

    public function getCurrentRoleType()
    {
        return UserService::ROLE_TYPE_CUSTOMER;
    }

    /**
     * return
     */
    public function getBuilder(): object
    {
        $query = ContactOrg::listableByCustomer();

        if (! empty($this->is_banned)) {
            $query->where('is_banned', 'like', '%'.$this->is_banned.'%');
        }

        if (! empty($this->name)) {
            $query->where('name', 'like', '%'.$this->name.'%');
        }

        if (! empty($this->long_name)) {
            $query->where('long_name', 'like', '%'.$this->long_name.'%');
        }

        if (! empty($this->location)) {
            $query->where('location', 'like', '%'.$this->location.'%');
        }

        if (! empty($this->city)) {
            $query->where('city', 'like', '%'.$this->city.'%');
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
                self::PROPERTY => 'is_banned',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.IsBanned',
                self::TRANSLATE_CELL_DATA => true,
            ],
            [
                self::PROPERTY => 'name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Name',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'long_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.LongName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'location',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.Location',
                self::TRANSLATE_CELL_DATA => false,
                self::USE_LABEL_OF_ENUM => Location::class,
            ],
            [
                self::PROPERTY => 'city',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.HQCity',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
