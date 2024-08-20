<?php

namespace Domain\System\Livewire;

use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\System\Models\VisitLog;
use Domain\User\Services\UserService;

final class VisitLogList extends BaseListComponent
{
    /**
     * @var string
     */
    public $user_name;

    /**
     * @var string
     */
    public $contact_name;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $ip_address;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $user_agent;

    /**
     * @var int
     */
    public $count_of_visits;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = null;

    // public $modalViewRoute = 'alma';

    public $entityClassReference = 'visitLog';

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
        return 'visit-log-list';
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
        $query = VisitLog::listableByAdmin();

        // dump($query->get());exit;

        if (! empty($this->user_name)) {
            $query->where('user_name', 'like', '%'.$this->user_name.'%');
        }

        if (! empty($this->contact_name)) {
            $query->where('contact_name', 'like', '%'.$this->contact_name.'%');
        }

        if (! empty($this->url)) {
            $query->where('url', 'like', '%'.$this->url.'%');
        }

        if (! empty($this->ip_address)) {
            $query->where('ip_address', 'like', '%'.$this->ip_address.'%');
        }

        if (! empty($this->host)) {
            $query->where('host', 'like', '%'.$this->host.'%');
        }

        if (! empty($this->user_agent)) {
            $query->where('user_agent', 'like', '%'.$this->user_agent.'%');
        }

        if (! empty($this->count_of_visits)) {
            $query->where('count_of_visits', 'like', '%'.$this->count_of_visits.'%');
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
                self::PROPERTY => 'user_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.AdminName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'contact_name',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'user.CustomerName',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'url',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.URL',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'ip_address',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.IPAddress',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'host',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.Host',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'user_agent',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.UserAgent',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'count_of_visits',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_TEXT,
                self::TRANSLATION_REFERENCE => 'shared.CountOfVisits',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
