<?php

namespace Domain\Communication\Livewire;

use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Shared\Helpers\LaravelHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class EmailDispatchProcessList extends BaseListComponent
{
    /**
     * @var string
     */
    public $title_lang_ref;

    /**
     * @var string
     */
    public $count_dispatches;

    // /**
    //  * @var string
    //  */
    public $reference_code;

    // /**
    //  * @var string
    //  */
    // public $modified_value;

    // /**
    //  * @var string
    //  */
    // public $host;

    // /**
    //  * @var string
    //  */
    // public $created_at;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = null;
    // public $editRoute = 'customer.communication.email-dispatch.list';

    public $applyRowRoute = true;

    public $componentClass = EmailDispatchProcessList::class;

    public $entityClassReference = 'userActivityLog';

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
        $this->sortDirection = 'DESC';
    }

    public static function getComponentName(): string
    {
        return 'email-dispatch-process-list';
    }

    public static function getRowRouteComponents($entityObject)
    {
        return [
            'type' => 'view',
            'name' => 'customer.communication.email-dispatch.list',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['communicationDispatchProcess' => $entityObject]),
        ];
    }

    // public function getEditRouteComponents(): array
    // {
    //     return [
    //         'name' => 'customer.communication.email-dispatch.list',
    //         'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['communicationDispatchProcess' => $this->getEntityObject()]),
    //     ];
    // }

    public function getCurrentRoleType()
    {
        return UserService::ROLE_TYPE_ADMIN;
    }

    /**
     * return
     */
    public function getBuilder(): object
    {
        $query = CommunicationDispatchProcess::listableByCustomer();

        if (! empty($this->title_lang_ref)) {
            $query->where('communicationcampaigns.', 'like', '%'.$this->title_lang_ref.'%');
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
                self::TABLE_ALIAS => 'communicationdispatchprocesses',
                self::PROPERTY_ALIAS => 'id',
                self::PROPERTY => 'id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'ID',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationcampaigns',
                self::PROPERTY_ALIAS => 'title_lang_ref',
                self::PROPERTY => 'title_lang_ref',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.Title',
                self::TRANSLATE_CELL_DATA => true,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatchprocesses',
                self::PROPERTY_ALIAS => 'count_dispatches',
                self::PROPERTY => 'count_dispatches',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'communication.CountDispatches',
                self::TRANSLATE_CELL_DATA => false,
                self::DISPLAY_SORTER => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatchprocesses',
                self::PROPERTY_ALIAS => 'started_at',
                self::PROPERTY => 'started_at',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.StartedAt',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatchprocesses',
                self::PROPERTY_ALIAS => 'finished_at',
                self::PROPERTY => 'finished_at',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.FinishedAt',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }

    public function render()
    {
        $objectCollection = $this->getPaginatorCollection();
        $this->objectArray = $objectCollection->items();
        $totalPagesCount = LaravelHelper::getTotalPagesCount($objectCollection);

        return view('communication.email-dispatch-process.list-data-table', [
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
