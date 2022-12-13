<?php

namespace App\Services;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Resources\InvoiceResource;
use App\Models\Company;
use App\Models\Invoice;
use App\Utils\Enum\EnumForCompany;
use App\Utils\Enum\EnumForInvoice;
use App\Utils\Enum\EnumForRole;
use App\Utils\Enum\EnumForStatus;
use App\Utils\Enum\EnumForUser;
use Illuminate\Support\Facades\Auth;

class InvoiceServices
{
    public function __construct(AuthController $authController)
    {
        $this->authController = $authController;
    }

    public function listInvoices($id)
    {
        try {
            $user = Auth::user();
            $role = $this->authController->verifyRoleUserAndPermission($user);

            if ($id === 'all') {
                if ($role !== EnumForRole::ROLE1) {
                    return serviceResponse(EnumForStatus::OK, EnumForInvoice::NOT_PERMISSIONS);
                }

                $invoices = Invoice::where('status', true)->get();
            }else {
                if (!is_numeric($id)) {
                    return serviceResponse(EnumForStatus::NOT_FOUND);
                }

                $companies = Company::where('id', $id)
                    ->where('user_id', $user['id'])
                    ->where('status', true)
                    ->first();

                if (is_null($companies)) {
                    return serviceResponse(EnumForStatus::OK, EnumForCompany::COMPANY_NOT_FOUND);
                }

                $invoices = Invoice::where('company_id', $id)
                    ->where('status', true)
                    ->paginate(10);
            }

            if ($invoices->isEmpty()) {
                return serviceResponse(EnumForStatus::OK, EnumForInvoice::NO_INVOICES);
            }

            return serviceResponse(EnumForStatus::OK, $invoices);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function saveInvoice($companyId, $data)
    {
        try {
            if (is_null($data)) {
                return serviceResponse(EnumForStatus::MESSAGE_200, EnumForInvoice::ERROR_DATA_INVOICE);
            }

            $user = Auth::user();
            $role = $this->authController->verifyRoleUserAndPermission($user);

            if (is_null($user)) {
                return serviceResponse(EnumForStatus::MESSAGE_200, EnumForUser::USER_NOT_FOUND);
            }

            if ($role === EnumForRole::ROLE1) {
                $company = Company::where('id', $companyId)
                    ->where('status', true)
                    ->first();
            }else {
                $company = Company::where('id', $companyId)
                    ->where('user_id', $user['id'])
                    ->where('status', true)
                    ->first();

            }

            if (is_null($company)) {
                return serviceResponse(EnumForStatus::MESSAGE_200, EnumForCompany::COMPANY_NOT_FOUND);
            }

            $details = $data['details'];
            $totalDetails = [];

            foreach($details as $detail) {
                $totalDetails[] = [
                    'name' => $detail['name'],
                    'quantity' => $detail['quantity'],
                    'unit_value' => $detail['unit_value'],
                    'total' => $detail['quantity'] * $detail['unit_value']
                ];
            }

            $data['details'] = json_encode($totalDetails);

            $data['company_id'] = $companyId;

            $invoice = Invoice::create($data);

            return serviceResponse(EnumForStatus::CREATED, new InvoiceResource($invoice));
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteInvoice($companyId, $invoiceId)
    {
        try {
            $user = Auth::user();
            $role = $this->authController->verifyRoleUserAndPermission($user);

            if ($role === EnumForRole::ROLE2) {
                return serviceResponse(EnumForStatus::UNAUTHORIZED, EnumForStatus::MESSAGE_401);
            }

            $company = Company::find($companyId);

            if (is_null($company)) {
                return serviceResponse(EnumForStatus::OK, EnumForCompany::COMPANY_NOT_FOUND);
            }

            $invoice = Invoice::where('id', $invoiceId)
                ->where('company_id', $companyId)
                ->where('status', true)
                ->first();

            if (is_null($invoice)) {
                return serviceResponse(EnumForStatus::OK, EnumForInvoice::NOT_FOUND_INVOICE);
            }

            $invoice->delete();

            return serviceResponse(EnumForStatus::OK, EnumForInvoice::DELETED_INVOICE);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

}
