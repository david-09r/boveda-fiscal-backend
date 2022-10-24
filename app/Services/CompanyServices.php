<?php

namespace App\Services;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Utils\Enum\EnumForCompany;
use App\Utils\Enum\EnumForRole;
use App\Utils\Enum\EnumForStatus;
use App\Utils\Enum\EnumForUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CompanyServices
{
    public function __construct(AuthController $auth)
    {
        $this->auth = $auth;
    }

    public function listForUserOfCompanies()
    {
        try {
            $user = Auth::user();
            $role = $this->auth->verifyRoleUserAndPermission($user);

            if ($role === EnumForRole::ROLE1) {
                $companies = Company::where('status', true)->get();

            }elseif ($role === EnumForRole::ROLE2) {
                $companies = Company::where('user_id', $user['id'])
                    ->where('status', true)->get();
            }else {
                return serviceResponse(EnumForStatus::OK, EnumForUser::USER_NOT_FOUND);
            }

            return serviceResponse(EnumForStatus::OK, CompanyResource::collection($companies));
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function storeCompanyByAdmin($data)
    {
        try {
            $role = $this->auth->verifyRoleUserAndPermission(Auth::user());

            if (is_null($role)) {
                return serviceResponse(EnumForStatus::OK, EnumForUser::USER_NOT_FOUND);
            }

            if ($role !== EnumForRole::ROLE1) {
                return serviceResponse(EnumForStatus::OK, EnumForCompany::NOT_PERMISSIONS);
            }

            $company = Company::create($data);

            return serviceResponse(EnumForStatus::CREATED, new CompanyResource($company));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function showCompany($companyId)
    {
        try {
            $user = Auth::user();

            if (!isset($user)) {
                return serviceResponse(EnumForStatus::OK, EnumForUser::USER_NOT_FOUND);
            }

            $role = $this->auth->verifyRoleUserAndPermission($user);

            if ($role === EnumForRole::ROLE1) {
                $company = Company::where('id', $companyId)
                    ->where('status', true)
                    ->first();
            }else if ($role === EnumForRole::ROLE2) {
                $company = Company::where('id', $companyId)
                    ->where('user_id', $user['id'])
                    ->where('status', true)
                    ->first();
            }else {
                return serviceResponse(EnumForStatus::OK, EnumForUser::USER_NOT_FOUND);
            }

            return serviceResponse(EnumForStatus::OK, new CompanyResource($company));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateCompanyByAdmin($companyId, $data)
    {
        try {
            $user = Auth::user();
            $role = $this->auth->verifyRoleUserAndPermission($user);

            if (is_null($role)) {
                return serviceResponse(EnumForStatus::OK, EnumForUser::USER_NOT_FOUND);
            }

            if ($role === EnumForRole::ROLE1) {
                $company = Company::find($companyId);

                if (is_null($company)) {
                    return serviceResponse(EnumForStatus::OK, EnumForCompany::COMPANY_NOT_FOUND);
                }

                $company->update($data);
            } else {
                $company = Company::where('id', $companyId)
                    ->where('user_id', $user['id'])
                    ->where('status', true)
                    ->first();

                if (is_null($company)) {
                    return serviceResponse(EnumForStatus::OK, EnumForCompany::COMPANY_NOT_FOUND);
                }

                $company->update($data);
            }

            $company = Company::find($companyId);
            return serviceResponse(EnumForStatus::OK, new CompanyResource($company));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteCompanyByAdmin($companyId)
    {
        try {
            $user = Auth::user();
            $role = $this->auth->verifyRoleUserAndPermission($user);

            if ($role !== EnumForRole::ROLE1) {
                return serviceResponse(EnumForStatus::OK, EnumForCompany::NOT_PERMISSIONS);
            }

            $company = Company::find($companyId);

            if (is_null($company)) {
                return serviceResponse(EnumForStatus::MESSAGE_200, EnumForCompany::COMPANY_NOT_FOUND);
            }

            $company->delete();

            return serviceResponse(EnumForStatus::OK, EnumForCompany::COMPANY_DELETED);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}