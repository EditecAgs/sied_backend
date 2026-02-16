<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\State;
use App\Models\Municipality;
use App\Models\Subsystem;
use App\Models\AcademicPeriod;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $stateAgs = State::where('name', 'Aguascalientes')->first();
        $stateBc = State::where('name', 'Baja California')->first();
        $stateCdmx = State::where('name', 'Ciudad de México')->first();
        $stateSin = State::where('name', 'Sinaloa')->first();
        $stateTamps = State::where('name', 'Tamaulipas')->first();
        $stateSlp = State::where('name', 'San Luis Potosí')->first();
        $stateGto = State::where('name', 'Guanajuato')->first();
        $stateHgo = State::where('name', 'Hidalgo')->first();
        $stateQro = State::where('name', 'Querétaro')->first();
        $stateVer = State::where('name', 'Veracruz')->first();
        $stateQroo = State::where('name', 'Quintana Roo')->first();
        
        $munAgs = Municipality::where('name', 'Aguascalientes')
            ->where('id_state', $stateAgs?->id)->first();
        $munTijuana = Municipality::where('name', 'Tijuana')
            ->where('id_state', $stateBc?->id)->first();
        $munIztapalapa = Municipality::where('name', 'Iztapalapa')
            ->where('id_state', $stateCdmx?->id)->first();
        $munCuliacan = Municipality::where('name', 'Culiacán')
            ->where('id_state', $stateSin?->id)->first();
        $munCdVictoria = Municipality::where('name', 'Victoria')
            ->where('id_state', $stateTamps?->id)->first();
        $munSoledad = Municipality::where('name', 'Soledad de Graciano Sánchez')
            ->where('id_state', $stateSlp?->id)->first();
        $munLeon = Municipality::where('name', 'León')
            ->where('id_state', $stateGto?->id)->first();
        $munHuichapan = Municipality::where('name', 'Huichapan')
            ->where('id_state', $stateHgo?->id)->first();
        $munQueretaro = Municipality::where('name', 'Querétaro')
            ->where('id_state', $stateQro?->id)->first();
        $munOrizaba = Municipality::where('name', 'Orizaba')
            ->where('id_state', $stateVer?->id)->first();
        $munChetumal = Municipality::where('name', 'Othón P. Blanco')
            ->where('id_state', $stateQroo?->id)->first();
        $munCelaya = Municipality::where('name', 'Celaya')
            ->where('id_state', $stateGto?->id)->first();
            

        $subsistemaTec = Subsystem::where('name', 'Tecnológico Nacional de México')->first();
        $subsistemaUa = Subsystem::where('name', 'Universidad Autónoma')->first();
        $subsistemaUp = Subsystem::where('name', 'Universidad Politécnica')->first();
        $subsistemaIp = Subsystem::where('name', 'Instituto Politécnico Nacional')->first();
        

        $periodoSemestral = AcademicPeriod::where('name', 'Semestral')->first();
        

        if (!$stateAgs || !$munAgs || !$subsistemaTec || !$periodoSemestral) {
            $this->command->error('Faltan datos referenciados. Ejecuta primero los seeders: State, Municipality, Subsystem, AcademicPeriod');
            return;
        }

        $institutions = [
            [
                'name' => 'Instituto Tecnológico de Aguascalientes',
                'street' => 'Av. Adolfo López Mateos Ote.',
                'external_number' => '1801',
                'neighborhood' => 'Fracc. Bona Gens',
                'postal_code' => '20256',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'city' => 'Aguascalientes',
                'id_subsystem' => $subsistemaTec->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Universidad Autónoma de Aguascalientes',
                'street' => 'Calle Falsa',
                'external_number' => 'S/N',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20257',
                'id_state' => $stateAgs->id,
                'id_municipality' => $munAgs->id,
                'city' => 'Aguascalientes',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Politécnico Nacional',
                'street' => 'Calle Falsa',
                'external_number' => '1802',
                'neighborhood' => 'Fracc. Falso',
                'postal_code' => '20256',
                'id_state' => $stateCdmx->id,
                'id_municipality' => $munIztapalapa?->id,
                'city' => 'Ciudad de México',
                'id_subsystem' => $subsistemaIp?->id ?? $subsistemaUp->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Culiacán',
                'street' => 'Av. Juan de Dios Bátiz',
                'external_number' => '310 Pte.',
                'neighborhood' => 'Col. Guadalupe',
                'postal_code' => '80220',
                'id_state' => $stateSin->id,
                'id_municipality' => $munCuliacan?->id,
                'city' => 'Culiacán',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Tijuana',
                'street' => 'Calzada Tecnológico',
                'external_number' => '12950',
                'neighborhood' => 'Fracc. Tomás Aquino',
                'postal_code' => '22414',
                'id_state' => $stateBc->id,
                'id_municipality' => $munTijuana?->id,
                'city' => 'Tijuana',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Ciudad Victoria',
                'street' => 'Boulevard Emilio Portes Gil',
                'external_number' => '1301 Pte.',
                'neighborhood' => 'A.P. 175',
                'postal_code' => '87010',
                'id_state' => $stateTamps->id,
                'id_municipality' => $munCdVictoria?->id,
                'city' => 'Ciudad Victoria',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de San Luis Potosí',
                'street' => 'Av. Tecnológico',
                'external_number' => 's/n',
                'neighborhood' => 'Col. Unidad Ponciano Arriaga',
                'postal_code' => '78437',
                'id_state' => $stateSlp->id,
                'id_municipality' => $munSoledad?->id,
                'city' => 'Soledad de Graciano Sánchez',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de León',
                'street' => 'Blvd. Juan Alonso de Torres Pte.',
                'external_number' => '3542',
                'neighborhood' => 'San José de Piletas',
                'postal_code' => '37316',
                'id_state' => $stateGto->id,
                'id_municipality' => $munLeon?->id,
                'city' => 'León',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico Superior de Huichapan',
                'street' => 'Domicilio Conocido',
                'external_number' => 's/n',
                'neighborhood' => 'El Saucillo',
                'postal_code' => '42411',
                'id_state' => $stateHgo->id,
                'id_municipality' => $munHuichapan?->id,
                'city' => 'Huichapan',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Querétaro',
                'street' => 'Av. Tecnológico',
                'external_number' => 's/n',
                'neighborhood' => 'Col. Centro Histórico',
                'postal_code' => '76000',
                'id_state' => $stateQro->id,
                'id_municipality' => $munQueretaro?->id,
                'city' => 'Querétaro',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Orizaba',
                'street' => 'Avenida Oriente 9',
                'external_number' => 'No. 852',
                'neighborhood' => 'Col. Emiliano Zapata',
                'postal_code' => '94320',
                'id_state' => $stateVer->id,
                'id_municipality' => $munOrizaba?->id,
                'city' => 'Orizaba',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Chetumal',
                'street' => 'Avenida Insurgentes',
                'external_number' => 's/n',
                'neighborhood' => 'Col. Centro',
                'postal_code' => '77000',
                'id_state' => $stateQroo->id,
                'id_municipality' => $munChetumal?->id,
                'city' => 'Chetumal',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Iztapalapa II',
                'street' => 'Av. Tecnológico',
                'external_number' => 's/n',
                'neighborhood' => 'Col. San Lázaro',
                'postal_code' => '09850',
                'id_state' => $stateCdmx->id,
                'id_municipality' => $munIztapalapa?->id,
                'city' => 'Ciudad de México',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Iztapalapa',
                'street' => 'Av. Tecnológico',
                'external_number' => 's/n',
                'neighborhood' => 'Col. San Lázaro',
                'postal_code' => '09850',
                'id_state' => $stateCdmx->id,
                'id_municipality' => $munIztapalapa?->id,
                'city' => 'Ciudad de México',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
            [
                'name' => 'Instituto Tecnológico de Celaya',
                'street' => 'Av. Tecnológico',
                'external_number' => 's/n',
                'neighborhood' => 'Col. Industrial',
                'postal_code' => '38010',
                'id_state' => $stateGto->id,
                'id_municipality' => $munCelaya?->id,
                'city' => 'Celaya',
                'id_subsystem' => $subsistemaUa->id,
                'id_academic_period' => $periodoSemestral->id,
                'image' => ''
            ],
        ];

        foreach ($institutions as $institution) {
            Institution::updateOrCreate(
                ['name' => $institution['name']],
                $institution
            );
        }

        $this->command->info('Instituciones creadas/actualizadas correctamente');
    }
}