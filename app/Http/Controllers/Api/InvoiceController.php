<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InvoiceServices;
use App\Utils\Enum\EnumForStatus;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(InvoiceServices $invoiceServices)
    {
        $this->service = $invoiceServices;
    }

    public function index()
    {
        try {
            $response = $this->service->listInvoice();
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }
}
