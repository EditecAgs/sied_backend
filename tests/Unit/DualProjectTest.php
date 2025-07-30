<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DualProjectTest extends TestCase{

    use RefreshDatabase;
    public function testRetrieveListOfDualProjects()
    {
        $response = $this->getJson(route('dual-projects-reported'));
        //dump($response->json());

        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }

    public function testCreateDualProject(){

        $data = [
            "id_institution"=>1,
            "has_report"=>"1",
            "name_report"=>"report name",
            "number_men"=>1,
            "number_women"=>1,
            "period_start"=>"10/10/2024",
            "period_end"=>"10/10/2025",
            "amount"=>0,
            "id_dual_area"=>1,
            "status_document"=>1,
            "economic_support"=>"1",
            "id_organization"=>"1",
            "control_number"=>"1asd089u1",
            "name_student"=>"1asd089u1",
            "lastname"=>"1asd089u1",
            "gender"=>"Masculino",
            "semester"=>1,
            "id_career"=>1,
            "id_specialty"=>1
        ];

        $response = $this->postJson(route('dual-projects-create'), $data);
        //dump($response->json());
        $response->assertStatus(201);


        $dual_projects = $this->getJson(route('dual-projects-reported'));
        //dump($dual_projects->json());

        $dual_projects->assertStatus(200);
        $dual_projects->assertJsonIsArray();
        $dual_projects->assertJsonCount(1);
    }
}
