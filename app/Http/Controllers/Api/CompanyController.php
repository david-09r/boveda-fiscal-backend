<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Services\CompanyServices;
use App\Utils\Enum\EnumForStatus;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(CompanyServices $companyServices)
    {
        $this->service = $companyServices;
    }

    public function index()
    {
        try {
            $response = $this->service->listForUserOfCompanies();
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Throwable $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CompanyRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->service->storeCompanyByAdmin($data);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->service->showCompany($id);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function update($id, CompanyRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->service->updateCompanyByAdmin($id, $data);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePermanent($id)
    {
        try {
            $response = $this->service->deleteCompanyByAdmin($id);
            return bodyResponse($response['statusCode'], $response['data']);
        }catch (\Exception $e){
            return bodyError($e->getMessage(), EnumForStatus::INTERNAL_SERVER_ERROR);
        }

    }
}
