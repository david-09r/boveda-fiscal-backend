<?php

namespace App\Services;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Utils\Enum\EnumForInvoice;
use App\Utils\Enum\EnumForStatus;

class InvoiceServices
{
    public function listInvoice()
    {
        try {
            $invoices = Invoice::where('status', true)->get();

            if ($invoices->isEmpty()) {
                return serviceResponse(EnumForStatus::OK, EnumForInvoice::NO_INVOICES);
            }

            return serviceResponse(EnumForStatus::OK, InvoiceResource::collection($invoices));
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}