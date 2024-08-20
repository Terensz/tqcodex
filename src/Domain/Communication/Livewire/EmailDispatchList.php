<?php

namespace Domain\Communication\Livewire;

use Domain\Communication\Builders\CommunicationDispatchBuilder;
use Domain\Communication\Models\CommunicationDispatch;
use Domain\Communication\Models\CommunicationDispatchProcess;
use Domain\Shared\Helpers\LaravelHelper;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class EmailDispatchList extends BaseListComponent
{
    public CommunicationDispatchProcess $communicationDispatchProcess;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $sender_address;

    /**
     * @var string
     */
    public $sender_name;

    /**
     * @var string
     */
    public $recipient_address;

    /**
     * @var string
     */
    public $recipient_name;

    // /**
    //  * @var string
    //  */
    public $sent_at;

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
    // public $editRoute = 'customer.communication.email-dispatch.edit';
    public $editRoute = null;

    public $applyRowRoute = true;

    public $componentClass = EmailDispatchList::class;

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
        // dump($this->communicationDispatchProcess);exit;
        // dump('email-dispatch-list');exit;
        // $this->sortColumn = 'communicationdispatchprocesses.id';
        $this->sortDirection = 'DESC';
        // dump($this->getRowRouteComponents(CommunicationDispatch::find(1)));exit;
    }

    public static function getComponentName(): string
    {
        return 'email-dispatch-list';
    }

    public static function getRowRouteComponents($entityObject)
    {
        return [
            'type' => 'view',
            'name' => 'customer.communication.email-dispatch.view',
            'paramArray' => UserService::getRouteParamArray(UserService::ROLE_TYPE_CUSTOMER, ['communicationDispatch' => $entityObject]),
        ];
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
        $query = CommunicationDispatch::listableByCustomer($this->communicationDispatchProcess->id ? $this->communicationDispatchProcess->id : null);

        // dump($query)->get(); exit;
        // $query = CommunicationDispatchBuilder::getCurrentUsersEmailListQuery($this->communicationDispatchProcess);

        // dump($query->get());exit;

        if (! empty($this->sender_address)) {
            $query->where('communicationdispatches.sender_address', 'like', '%'.$this->sender_address.'%');
        }

        if (! empty($this->sender_name)) {
            $query->where('communicationdispatches.sender_name', 'like', '%'.$this->sender_name.'%');
        }

        if (! empty($this->recipient_address)) {
            $query->where('communicationdispatches.recipient_address', 'like', '%'.$this->recipient_address.'%');
        }

        if (! empty($this->recipient_name)) {
            $query->where('communicationdispatches.recipient_name', 'like', '%'.$this->recipient_name.'%');
        }

        if (! empty($this->subject)) {
            $query->where('communicationdispatches.subject', 'like', '%'.$this->subject.'%');
        }

        $query
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }

    public function getTableData(): array
    {
        return [
            // [
            //     self::PROPERTY => 'title_lang_ref',
            //     self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
            //     self::TRANSLATION_REFERENCE => 'shared.Title',
            //     self::TRANSLATE_CELL_DATA => true,
            // ],
            // [
            //     self::PROPERTY => 'reference_code',
            //     self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
            //     self::TRANSLATION_REFERENCE => 'communication.ReferenceCode',
            //     self::TRANSLATE_CELL_DATA => false,
            // ],
            [
                self::TABLE_ALIAS => 'communicationdispatches',
                self::PROPERTY_ALIAS => 'subject',
                self::PROPERTY => 'subject',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'communication.Subject',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatches',
                self::PROPERTY_ALIAS => 'sender_address',
                self::PROPERTY => 'sender_address',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'communication.SenderEmailAddress',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatches',
                self::PROPERTY_ALIAS => 'sender_name',
                self::PROPERTY => 'sender_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'communication.SenderName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatches',
                self::PROPERTY_ALIAS => 'recipient_address',
                self::PROPERTY => 'recipient_address',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'communication.RecipientEmailAddress',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::TABLE_ALIAS => 'communicationdispatches',
                self::PROPERTY_ALIAS => 'recipient_name',
                self::PROPERTY => 'recipient_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'communication.RecipientName',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }

    public function render()
    {
        $objectCollection = $this->getPaginatorCollection();
        $this->objectArray = $objectCollection->items();
        $totalPagesCount = LaravelHelper::getTotalPagesCount($objectCollection);

        return view('communication.email-dispatch.list-data-table', [
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
