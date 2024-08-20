<?php

namespace Domain\Customer\Livewire;

use Domain\Customer\Models\OrgAddress;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class OrgAddressList extends BaseListComponent
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $organization_name;

    /**
     * @var string
     */
    public $title;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'customer.project.org-address.edit';

    public $entityClassReference = 'orgAddress';

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
        return 'org-address-list';
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
        $query = OrgAddress::listableByCustomer();

        if (! empty($this->organization_name)) {
            $query->where('organization_name', 'like', '%'.$this->organization_name.'%');
        }

        if (! empty($this->title)) {
            $query->where('title', 'like', '%'.$this->title.'%');
        }

        $builder = $query
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $builder;
    }

    public function getTableData(): array
    {
        return [
            [
                self::PROPERTY => 'organization_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'customer.OrganizationName',
                self::TRANSLATE_CELL_DATA => true,
            ],
            [
                self::PROPERTY => 'title',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'customer.AddressTitle',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
