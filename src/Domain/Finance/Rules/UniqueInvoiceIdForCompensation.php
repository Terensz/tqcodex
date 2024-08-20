<?php

namespace Domain\Finance\Rules;

use Closure;
use Domain\Finance\Models\CompensationItem;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueInvoiceIdForCompensation implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recordFound = CompensationItem::where('invoice_id_for_compensation', $value)->first();

        if ($recordFound && $recordFound instanceof CompensationItem && (! $this->data || ! isset($this->data['compensationItem']) || ($this->data['compensationItem']['id'] !== $recordFound->id))) {
            $fail(__('shared.ThisValueIsAlreadyTaken', ['value' => __('finance.InvoiceIdForCompensation')]));
        }
    }
}
