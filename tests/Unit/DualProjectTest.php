<?php

namespace Tests\Unit;

use App\Models\DualProject;
use App\Models\DualProjectReport;
use App\Models\Institution;
use App\Models\OrganizationDualProject;
use App\Models\Student;
use Tests\TestCase;

class DualProjectTest extends TestCase
{
    /**
     * @test
     */
    public function retrieve_list_of_dual_projects()
    {
        $response = $this->getJson(route('dual-projects-reported'));
        // dump($response->json());

        $response->assertStatus(200);
        $response->assertJsonIsArray();
    }

    /**
     * @test
     */
    public function create_dual_project()
    {

        $institution = Institution::firstWhere('id', 1);

        $data = [
            'id_institution' => $institution->id,
            'has_report' => '1',
            'name_report' => 'report name',
            'number_men' => 1,
            'number_women' => 1,
            'period_start' => '10/10/2024',
            'period_end' => '10/10/2025',
            'amount' => 0,
            'id_dual_area' => 1,
            'status_document' => 1,
            'economic_support' => '1',
            'id_organization' => '1',
            'control_number' => '1asd089u1',
            'name_student' => '1asd089u1',
            'lastname' => '1asd089u1',
            'gender' => 'Masculino',
            'semester' => 1,
            'id_career' => 1,
            'id_specialty' => 1,
        ];

        $response = $this->postJson(route('dual-projects-create'), $data);
        // dump($response->json());
        $response->assertStatus(201);

        $dual_projects = $this->getJson(route('dual-projects-reported'));
        // dump($dual_projects->json());

        $dual_projects->assertStatus(200);
        $dual_projects->assertJsonIsArray();
        $dual_projects->assertJsonCount(1);
    }

    /**
     * @test
     */
    public function update_dual_project_without_report_to_with_report()
    {
        $dualProject = DualProject::create([
            'has_report' => 0,
            'id_institution' => 1,
        ]);

        $updateData = [
            'has_report' => 1,
            'id_institution' => 1,
            'name_report' => 'Nuevo Reporte Actualizado',
            'number_men' => 2,
            'number_women' => 3,
            'period_start' => '2024-01-01',
            'period_end' => '2024-06-30',
            'amount' => 5000,
            'id_dual_area' => 1,
            'status_document' => 1,
            'economic_support' => 1,
            'id_organization' => 1,
            'control_number' => 'A00123456',
            'name_student' => 'Juan',
            'lastname' => 'Pérez',
            'gender' => 'Masculino',
            'semester' => 5,
            'id_career' => 1,
            'id_specialty' => 1,
        ];

        $response = $this->putJson(route('dual-projects-update', $dualProject->id), $updateData);
        $response->assertStatus(204);
        $updatedProject = DualProject::find($dualProject->id);
        $this->assertEquals(1, $updatedProject->has_report);
        $this->assertNotNull($updatedProject->dualProjectReports);
        $this->assertNotNull($updatedProject->organizationDualProjects);
        $this->assertNotNull($updatedProject->students);
    }

    /**
     * @test
     */
    public function update_dual_project_with_report()
    {
        $dualProject = DualProject::create([
            'has_report' => 1,
            'id_institution' => 1,
        ]);

        DualProjectReport::create([
            'dual_project_id' => $dualProject->id,
            'name' => 'Reporte Inicial',
            'number_men' => 1,
            'number_women' => 1,
            'id_dual_area' => 1,
            'period_start' => '2024-01-01',
            'period_end' => '2024-06-30',
            'status_document' => 1,
            'economic_support' => 1,
            'amount' => 1000,
        ]);

        OrganizationDualProject::create([
            'id_dual_project' => $dualProject->id,
            'id_organization' => 1,
        ]);

        Student::create([
            'id_dual_project' => $dualProject->id,
            'control_number' => 'A00123456',
            'name' => 'Estudiante Inicial',
            'lastname' => 'Apellido Inicial',
            'gender' => 'Masculino',
            'semester' => 3,
            'id_institution' => 1,
            'id_career' => 1,
            'id_specialty' => 1,
        ]);

        $updateData = [
            'has_report' => 1,
            'id_institution' => 1,
            'name_report' => 'Reporte Actualizado',
            'number_men' => 3,
            'number_women' => 2,
            'period_start' => '2024-07-01',
            'period_end' => '2024-12-31',
            'amount' => 1500,
            'id_dual_area' => 2,
            'status_document' => 2,
            'economic_support' => 2,
            'id_organization' => 1,
            'control_number' => 'B00987654',
            'name_student' => 'Estudiante Actualizado',
            'lastname' => 'Apellido Actualizado',
            'gender' => 'Femenino',
            'semester' => 4,
            'id_career' => 2,
            'id_specialty' => 1,
        ];

        $response = $this->putJson(route('dual-projects-update', $dualProject->id), $updateData);

        $response->assertStatus(204);

        $updatedProject = DualProject::with(['dualProjectReports', 'organizationDualProjects', 'students'])
            ->find($dualProject->id);

        $this->assertEquals('Reporte Actualizado', $updatedProject->dualProjectReports->name);
        $this->assertEquals(3, $updatedProject->dualProjectReports->number_men);
        $this->assertEquals(2, $updatedProject->dualProjectReports->number_women);
        $this->assertEquals(2, $updatedProject->dualProjectReports->id_dual_area);
        $this->assertEquals('2024-07-01', $updatedProject->dualProjectReports->period_start);
        $this->assertEquals('2024-12-31', $updatedProject->dualProjectReports->period_end);
        $this->assertEquals(1500, $updatedProject->dualProjectReports->amount);
        $this->assertEquals(2, $updatedProject->dualProjectReports->status_document);
        $this->assertEquals(2, $updatedProject->dualProjectReports->economic_support);

        $this->assertEquals(1, $updatedProject->organizationDualProjects->id_organization);

        $this->assertEquals('B00987654', $updatedProject->students->first()->control_number);
        $this->assertEquals('Estudiante Actualizado', $updatedProject->students->first()->name);
        $this->assertEquals('Apellido Actualizado', $updatedProject->students->first()->lastname);
        $this->assertEquals('Femenino', $updatedProject->students->first()->gender);
        $this->assertEquals(4, $updatedProject->students->first()->semester);
        $this->assertEquals(2, $updatedProject->students->first()->id_career);
        $this->assertEquals(1, $updatedProject->students->first()->id_specialty);
    }

    /**
     * @test
     */
    public function delete_project_without_report()
    {
        $project = DualProject::create([
            'has_report' => 0,
            'id_institution' => 1,
        ]);

        $response = $this->deleteJson(route('dual-projects-delete', $project->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted('dual_projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('dual_project_reports', [
            'dual_project_id' => $project->id,
        ]);
    }

    /**
     * @test
     */
    public function delete_project_with_report()
    {
        $project = DualProject::create([
            'has_report' => 1,
            'id_institution' => 1,
        ]);

        $report = DualProjectReport::create([
            'dual_project_id' => $project->id,
            'name' => 'Reporte Inicial',
            'number_men' => 1,
            'number_women' => 1,
            'id_dual_area' => 1,
            'period_start' => '2024-01-01',
            'period_end' => '2024-06-30',
            'status_document' => 1,
            'economic_support' => 1,
            'amount' => 1000,
        ]);

        $organization = OrganizationDualProject::create([
            'id_dual_project' => $project->id,
            'id_organization' => 1,
        ]);

        $student = Student::create([
            'id_dual_project' => $project->id,
            'control_number' => 'A00123456',
            'name' => 'Estudiante Inicial',
            'lastname' => 'Apellido Inicial',
            'gender' => 'Masculino',
            'semester' => 3,
            'id_institution' => 1,
            'id_career' => 1,
            'id_specialty' => 1,
        ]);

        $response = $this->deleteJson(route('dual-projects-delete', $project->id));

        $response->assertNoContent();

        $this->assertSoftDeleted($project);

        $this->assertSoftDeleted('dual_project_reports', ['id' => $report->id]);
        $this->assertSoftDeleted('organizations_dual_projects', ['id' => $organization->id]);
        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }
}
