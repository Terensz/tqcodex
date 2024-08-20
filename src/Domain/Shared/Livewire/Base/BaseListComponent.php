<?php

namespace Domain\Shared\Livewire\Base;

use Domain\Shared\Exports\Export;
use Domain\Shared\Helpers\LaravelHelper;
use Domain\Shared\Helpers\ListHelper;
use Domain\User\Services\UserService;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use ReflectionClass;

/**
 * This class is a general parent of all "list" type Livewire Component classes.
 * Please put only general data into this class!
 */
abstract class BaseListComponent extends BaseLivewireComponent
{
    use WithPagination;

    public const TABLE_ALIAS = 'tableAlias';

    public const MODEL_REFERENCE = 'modelReference';

    public const PROPERTY_ALIAS = 'propertyAlias';

    public const PROPERTY = 'property';

    public const WIRE_PROP = 'wireProperty';

    public const INPUT_TYPE = 'inputType';

    public const TRANSLATION_REFERENCE = 'translationReference';

    public const DISPLAY_SORTER = 'displaySorter';

    /**
     * If the current cell data need to be translated
     */
    public const TRANSLATE_CELL_DATA = 'translateCellData';

    const USE_LABEL_OF_ENUM = 'useLabelOfEnum';

    /**
     * If the input is a select, that will have options
     */
    public const OPTIONS = 'options';

    /**
     * Options row contains this
     */
    public const OPTION_PROPERTY_KEY_VALUE = 'value';

    /**
     * Options row contains this
     */
    public const OPTION_PROPERTY_KEY_DISPLAYED = 'displayed';

    public const TABLE_CELL_CLASSES = 'classes';

    public const TABLE_CELL_FORMATTER = 'formatter';

    public $componentClass = null;

    public $applyRowRoute = null;

    public $overriddenPerPage;

    public $objectArray;

    /**
     * @var string
     */
    public $sortDirection = 'ASC';

    /**
     * @var string
     */
    public $sortColumn = 'id';

    /**
     * @var bool
     */
    public $loading = false;

    public bool $showViewButton = false;

    /**
     * In the first version this option is not wired.
     * Later it will allow a modal to pop up instead of reload page to the edit route of the current entity object.
     *
     * @var bool
     */
    public $showEditModal = false;

    /**
     * Show or hide "Export to Excel" button in the data-table.
     *
     * @var bool
     */
    public $allowExportToExcel = false;

    /**
     * Reference to a class beginning with lowercase
     * We use it e.g. in route: '.../{user}'
     */
    public $entityClassReference = 'model';

    public function getCurrentRoleType()
    {
        return UserService::ROLE_TYPE_ADMIN;
    }

    public static function getComponentName(): string
    {
        return sprintf('%s-list', self::getEntity());
    }

    protected static function getEntity()
    {
        $reflection = new ReflectionClass(static::class);
        $className = $reflection->getShortName(); // Get the class name without namespace

        // Extract the part before "List" and convert it to lowercase
        $tagName = strtolower(substr($className, 0, -4)); // Assuming "List" is always at the end

        return $tagName;
    }

    abstract public function getBuilder(): object;

    abstract public function getTableData(): array;

    public function toggleSortDirection(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = ($this->sortDirection === 'ASC') ? 'DESC' : 'ASC';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'ASC';
        }
    }

    public function getPerPage()
    {
        return $this->overriddenPerPage ?: ListHelper::getPerPage();
    }

    public function getPaginatorCollection()
    {
        $builder = $this->getBuilder();

        return $builder->paginate($this->getPerPage());
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $objectCollection = $this->getPaginatorCollection();
        $this->objectArray = $objectCollection->items();
        $totalPagesCount = LaravelHelper::getTotalPagesCount($objectCollection);

        return view('common.general-data-table', [
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
            // 'classConstants' => $reflectionObject->getConstants()
        ]);
    }

    public function getShowRotue()
    {
        if (! $this->showViewButton) {
            return false;
        }

        return sprintf('admin.%s.show', self::getEntity());
    }

    public function getEditRoute()
    {
        return sprintf('admin.%s.edit', self::getEntity());
    }

    public function select($key, $value)
    {
        $this->$key = $value;

        return $this->search();
    }

    public function doSort($sortColumn)
    {
        $this->sortColumn = $sortColumn;
    }

    public function search()
    {
        $this->dispatch('rendered');
        // $this->resetPage();
    }

    public function openEditModal($id)
    {
        // $this->selectedRecord = User::findOrFail($id);
        $this->showEditModal = true;
    }

    public function exportToExcel()
    {
        $builder = $this->getBuilder();
        $collection = $builder->get();

        return Excel::download(new Export($collection), (new \DateTime)->format('Ymd-His').'.xlsx');
    }

    public function refreshList()
    {
        $this->resetPage();

        return $this->render();
    }
}
