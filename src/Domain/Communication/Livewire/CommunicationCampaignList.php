<?php

namespace Domain\Communication\Livewire;

use Domain\Communication\Models\CommunicationCampaign;
use Domain\Shared\Helpers\ValidationHelper;
use Domain\Shared\Livewire\Base\BaseListComponent;
use Domain\User\Services\UserService;

final class CommunicationCampaignList extends BaseListComponent
{
    /**
     * @var string
     */
    public $reference_code;

    /**
     * @var string
     */
    public $title_lang_ref;

    /**
     * @var string
     */
    public $is_published;

    /**
     * @var string
     */
    public $is_editable;

    /**
     * @var string
     */
    public $is_redispatchable;

    /**
     * Get this data from the routing file! Route::...->name('admin.admin.user.edit')
     * The data-table blade will automatically add "id" property of the looped entity object.
     * In this version only "id" field is accepted as the identifier.
     *
     * @var string
     */
    public $editRoute = null;

    public $entityClassReference = 'communicationCampaign';

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
        $this->sortColumn = 'communicationcampaigns.id';
        $this->sortDirection = 'DESC';
    }

    public static function getComponentName(): string
    {
        return 'communication-campaign-list';
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
        $query = CommunicationCampaign::listableByCustomer();

        // raw_subject
        // count_dispatches
        // reference_code
        // reference_code

        if (! empty($this->reference_code)) {
            $query->where('communicationcampaigns.reference_code', 'like', '%'.$this->reference_code.'%');
        }

        if (! empty($this->title_lang_ref)) {
            $query->where('communicationcampaigns.title_lang_ref', 'like', '%'.$this->title_lang_ref.'%');
        }

        if (! empty($this->is_published)) {
            $query->where('communicationcampaigns.is_published', $this->is_published);
        }

        $query
            ->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }

    public function getTableData(): array
    {
        return [
            [
                self::PROPERTY => 'reference_code',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'communication.ReferenceCode',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'title_lang_ref',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.Title',
                self::TRANSLATE_CELL_DATA => true,
            ],
            [
                self::PROPERTY => 'is_published',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'communication.IsPublished',
                self::TRANSLATE_CELL_DATA => false,
            ],
            [
                self::PROPERTY => 'is_editable',
                self::INPUT_TYPE => ValidationHelper::INPUT_TYPE_SHOW_ONLY,
                self::TRANSLATION_REFERENCE => 'shared.Editable',
                self::TRANSLATE_CELL_DATA => false,
            ],
        ];
    }
}
