<?php

namespace Domain\System\Livewire;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\System\Models\ExceptionLog;
use Domain\User\Services\UserService;

final class ExceptionLogList extends BaseListComponent
{
    /**
     * @var string
     */
    public $user_id;

    /**
     * @var string
     */
    public $contact_id;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $line;

    /**
     * @var int
     */
    public $trace;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = null;

    public $entityClassReference = 'exceptionLog';

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
        return 'exception-log-list';
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
        $query = ExceptionLog::query();

        if (! empty($this->user_id)) {
            $query->where('user_id', 'like', '%'.$this->user_id.'%');
        }

        if (! empty($this->contact_id)) {
            $query->where('contact_id', 'like', '%'.$this->contact_id.'%');
        }

        if (! empty($this->message)) {
            $query->where('message', 'like', '%'.$this->message.'%');
        }

        if (! empty($this->code)) {
            $query->where('code', 'like', '%'.$this->code.'%');
        }

        if (! empty($this->file)) {
            $query->where('file', 'like', '%'.$this->file.'%');
        }

        if (! empty($this->line)) {
            $query->where('line', 'like', '%'.$this->line.'%');
        }

        if (! empty($this->trace)) {
            $query->where('trace', 'like', '%'.$this->trace.'%');
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
                self::PROPERTY => 'user_id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.UserId',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'contact_id',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.ContactId',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'message',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Message',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'code',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Code',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'file',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.File',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'line',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Line',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'trace',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Trace',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
