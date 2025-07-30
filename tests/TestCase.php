<?php

namespace Tests;

use Database\Seeders\CareerSeeder;
use Database\Seeders\EconomicSupportSeeder;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\SpecialtySeeder;
use Database\Seeders\StateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(InstitutionSeeder::class);
        $this->seed(EconomicSupportSeeder::class);
        $this->seed(OrganizationSeeder::class);
        $this->seed(CareerSeeder::class);
        $this->seed(SpecialtySeeder::class);
        $this->seed(StateSeeder::class);
    }
}
