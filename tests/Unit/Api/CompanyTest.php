<?php

namespace Tests\Unit\Api;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_belong_to_user()
    {
        $company = Company::factory()->create();

        $this->assertInstanceOf(User::class, $company->user);
    }
}
