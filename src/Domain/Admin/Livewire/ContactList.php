<?php

namespace Domain\Admin\Livewire;

use Domain\Customer\Models\Contact;
use Domain\Customer\Models\ContactProfile;
use Domain\Shared\Builders\Base\BaseBuilder;
use Domain\Shared\Helpers\LaravelHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class ContactList extends BaseListComponent
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
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'admin.customer.contact.edit';

    public $entityClassReference = 'contact';

    /**
     * @var array<mixed>
     */
    // protected $listeners = ['refreshList' => 'refreshList'];

    /**
     * @var bool
     */
    public $allowExportToExcel = true;

    public function mount()
    {
        $this->sortColumn = 'id';
    }

    public static function getComponentName(): string
    {
        return 'contact-list';
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
        $query = Contact::listableByAdmin();

        if (! empty($this->lastname)) {
            $query->whereAssociatedPropertyIs('contactProfile', 'lastname', BaseBuilder::LIKE, $this->lastname);
        }

        if (! empty($this->firstname)) {
            $query->whereAssociatedPropertyIs('contactProfile', 'firstname', BaseBuilder::LIKE, $this->firstname);
        }

        if (! empty($this->email)) {
            $query->whereAssociatedPropertyIs('contactProfile', 'email', BaseBuilder::LIKE, $this->email);
        }

        $fullSortColumnString = '';
        foreach ($this->getTableData() as $tableDataRow) {
            if ($tableDataRow[self::PROPERTY_ALIAS] == $this->sortColumn) {
                if (isset($tableDataRow[self::TABLE_ALIAS])) {
                    $fullSortColumnString = $tableDataRow[self::TABLE_ALIAS].'.'.$tableDataRow[self::PROPERTY];
                }
            }
        }

        if (! empty($fullSortColumnString)) {
            $query->orderBy($fullSortColumnString, $this->sortDirection);
        }

        return $query;
    }

    public function getTableData(): array
    {
        return [
            [
                self::TABLE_ALIAS => 'contactprofiles',
                self::PROPERTY_ALIAS => ContactProfile::PROPERTY_LAST_NAME,
                self::PROPERTY => ContactProfile::PROPERTY_LAST_NAME,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Lastname',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'contactprofiles',
                self::PROPERTY_ALIAS => ContactProfile::PROPERTY_FIRST_NAME,
                self::PROPERTY => ContactProfile::PROPERTY_FIRST_NAME,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Firstname',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'contactprofiles',
                self::PROPERTY_ALIAS => ContactProfile::PROPERTY_EMAIL,
                self::PROPERTY => ContactProfile::PROPERTY_EMAIL,
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.Email',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'contacts',
                self::PROPERTY_ALIAS => 'created_at',
                self::PROPERTY => 'created_at',
                self::INPUT_TYPE => 'datetimePicker',
                self::TRANSLATION_REFERENCE => 'shared.CreatedAt',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }

    public function render()
    {
        $objectCollection = $this->getPaginatorCollection();
        $this->objectArray = $objectCollection->items();
        $totalPagesCount = LaravelHelper::getTotalPagesCount($objectCollection);

        return view('admin.customer.contact.list-data-table', [
            'componentClass' => $this->componentClass ?? null,
            'applyRowRoute' => $this->applyRowRoute ?? null,
            'roleType' => $this->getCurrentRoleType(),
            'sortDirection' => $this->sortDirection,
            'sortColumn' => $this->sortColumn,
            'tableData' => $this->getTableData(),
            'objectCollection' => $objectCollection,
            'totalPagesCount' => $totalPagesCount,
            'loading' => $this->loading,
            'showRoute' => $this->getShowRotue(),
            'editRoute' => $this->getEditRoute(),
            'showEditModal' => $this->showEditModal,
            'allowExportToExcel' => $this->allowExportToExcel,
            'entityClassReference' => $this->entityClassReference,
        ]);
    }
}
