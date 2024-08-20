<?php

namespace Domain\Project\Livewire;

use Domain\Finance\Models\CompensationItem;
use Domain\Shared\Helpers\LaravelHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class CompensationItemList extends BaseListComponent
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $contact_lastname;

    /**
     * @var string
     */
    public $contact_firstname;

    /**
     * @var string
     */
    public $creator_organization_name;

    /**
     * @var string
     */
    public $invoice_amount;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $invoice_id_for_compensation;

    /**
     * @var string
     */
    public $invoice_internal_id;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = 'customer.project.compensation-item.edit';

    public $entityClassReference = 'compensationItem';

    /**
     * @var array<mixed>
     */
    // protected $listeners = ['refreshList' => 'refreshList'];

    /**
     * @var bool
     */
    public $allowExportToExcel = true;

    public $sortColumn = 'id';

    public static function getComponentName(): string
    {
        return 'compensation-item-list';
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
        $query = CompensationItem::listableByCustomer();

        // $sql = $query->toSql();
        // echo nl2br($sql); exit;

        if (! empty($this->id)) {
            $query->where('id', 'like', '%'.$this->id.'%');
        }

        if (! empty($this->contact_lastname)) {
            $query->whereHas('contact', function ($subQuery) {
                $subQuery->whereHas('contactProfile', function ($subQuery) {
                    $subQuery->where('lastname', 'like', '%'.$this->contact_lastname.'%');
                });
            });
        }

        if (! empty($this->contact_firstname)) {
            $query->whereHas('contact', function ($subQuery) {
                $subQuery->whereHas('contactProfile', function ($subQuery) {
                    $subQuery->where('firstname', 'like', '%'.$this->contact_firstname.'%');
                });
            });
        }

        if (! empty($this->creator_organization_name)) {
            $query->whereHas('organization', function ($subQuery) {
                $subQuery->where('name', 'like', '%'.$this->creator_organization_name.'%');
            });
        }

        if (! empty($this->invoice_amount)) {
            $query->where('invoice_amount', 'like', '%'.$this->invoice_amount.'%');
        }

        if (! empty($this->currency)) {
            $query->where('currency', 'like', '%'.$this->currency.'%');
        }

        if (! empty($this->invoice_id_for_compensation)) {
            $query->where('invoice_id_for_compensation', 'like', '%'.$this->invoice_id_for_compensation.'%');
        }

        if (! empty($this->invoice_internal_id)) {
            $query->where('invoice_internal_id', 'like', '%'.$this->invoice_internal_id.'%');
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
                self::TABLE_ALIAS => 'compensationitems',
                self::PROPERTY_ALIAS => 'id',
                self::PROPERTY => 'id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'project.CompensationItemId',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'contactprofiles',
                self::PROPERTY_ALIAS => 'contact_lastname',
                self::PROPERTY => 'lastname',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'customer.CreatorLastName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'contactprofiles',
                self::PROPERTY_ALIAS => 'contact_firstname',
                self::PROPERTY => 'firstname',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'customer.CreatorFirstName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'creatororgs',
                self::PROPERTY_ALIAS => 'creator_organization_name',
                self::PROPERTY => 'name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.OrganizationName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'compensationitems',
                self::PROPERTY_ALIAS => 'invoice_amount',
                self::PROPERTY => 'invoice_amount',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'finance.InvoiceAmount',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'compensationitems',
                self::PROPERTY_ALIAS => 'currency',
                self::PROPERTY => 'currency',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'finance.Currency',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'compensationitems',
                self::PROPERTY_ALIAS => 'invoice_id_for_compensation',
                self::PROPERTY => 'invoice_id_for_compensation',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'finance.InvoiceIdForCompensation',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'compensationitems',
                self::PROPERTY_ALIAS => 'invoice_internal_id',
                self::PROPERTY => 'invoice_internal_id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'finance.InvoiceInternalId',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }

    public function render()
    {
        $objectCollection = $this->getPaginatorCollection();
        $this->objectArray = $objectCollection->items();
        $totalPagesCount = LaravelHelper::getTotalPagesCount($objectCollection);

        return view('project.customer.compensation-item.list-data-table', [
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
