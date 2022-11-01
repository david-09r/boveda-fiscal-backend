<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceServices;
use App\Utils\Enum\EnumForStatus;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(InvoiceServices $invoiceServices)
    {
        $this->service = $invoiceServices;
    }

    public function index($id)
    {
        try {
            $response = $this->service->listInvoices($id);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function store($companyId, InvoiceRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->service->saveInvoice($companyId, $data);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($companyId, $invoiceId)
    {
        try {
            $response = $this->service->deleteInvoice($companyId, $invoiceId);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e) {
            return bodyError($e->getPrevious(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }
}
