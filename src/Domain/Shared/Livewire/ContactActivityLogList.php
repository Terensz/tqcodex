<?php

namespace Domain\Shared\Livewire;

use Domain\Customer\Models\ContactActivityLog;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class ContactActivityLogList extends BaseListComponent
{
    /**
     * @var string
     */
    public $contact_id;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $modified_property;

    /**
     * @var string
     */
    public $original_value;

    /**
     * @var string
     */
    public $modified_value;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $created_at;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = null;

    public $entityClassReference = 'userActivityLog';

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
        return 'contact-activity-log-list';
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
        $query = ContactActivityLog::query();

        if (! empty($this->contact_id)) {
            $query->where('member_id', 'like', '%'.$this->contact_id.'%');
        }

        if (! empty($this->action)) {
            $query->where('action', 'like', '%'.$this->action.'%');
        }

        if (! empty($this->modified_property)) {
            $query->where('modified_property', 'like', '%'.$this->modified_property.'%');
        }

        if (! empty($this->original_value)) {
            $query->where('original_value', 'like', '%'.$this->original_value.'%');
        }

        if (! empty($this->modified_value)) {
            $query->where('modified_value', 'like', '%'.$this->modified_value.'%');
        }

        if (! empty($this->host)) {
            $query->where('host', 'like', '%'.$this->host.'%');
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
                self::PROPERTY => 'member_id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.ContactId',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'action',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Action',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'modified_property',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.ModifiedProperty',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'original_value',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.OriginalValue',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'modified_value',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.ModifiedValue',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'host',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Host',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
