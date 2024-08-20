<?php

namespace Domain\Project\Services;

use Domain\Finance\Enums\InvoiceType;
use Domain\Finance\Models\CompensationItem;

class CompensationAlgorythmService
{
    public static function claimsOf($organization_id)
    {
        $compencationItemCollection = CompensationItem::where(function ($query) use ($organization_id) {
            $query->where('invoice_type', InvoiceType::CLAIM)
                ->where('organization_id', $organization_id);
        })->orWhere(function ($query) use ($organization_id) {
            $query->where('invoice_type', InvoiceType::DEBT)
                ->where('partner_org_id', $organization_id);
        })->get();

        $summaryByCurrency = [];
        foreach ($compencationItemCollection as $compencationItem) {
            if ($compencationItem instanceof CompensationItem) {
                if (! isset($summaryByCurrency[$compencationItem->currency])) {
                    $summaryByCurrency[$compencationItem->currency] = 0;
                }

                $summaryByCurrency[$compencationItem->currency] += $compencationItem->invoice_amount;
            }
        }

        return [
            'compencationItemCollection' => $compencationItemCollection,
            'summaryByCurrency' => $summaryByCurrency,
        ];
    }

    public static function debtsOf($organization_id)
    {
        $compencationItemCollection = CompensationItem::where(function ($query) use ($organization_id) {
            $query->where('invoice_type', InvoiceType::DEBT)
                ->where('organization_id', $organization_id);
        })->orWhere(function ($query) use ($organization_id) {
            $query->where('invoice_type', InvoiceType::CLAIM)
                ->where('partner_org_id', $organization_id);
        })->get();

        $summaryByCurrency = [];
        foreach ($compencationItemCollection as $compencationItem) {
            if ($compencationItem instanceof CompensationItem) {
                if (! isset($summaryByCurrency[$compencationItem->currency])) {
                    $summaryByCurrency[$compencationItem->currency] = 0;
                }

                $summaryByCurrency[$compencationItem->currency] += $compencationItem->invoice_amount;
            }
        }

        return [
            'compencationItemCollection' => $compencationItemCollection,
            'summaryByCurrency' => $summaryByCurrency,
        ];
    }
}
