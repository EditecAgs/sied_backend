<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class DashboardExcelExport implements FromArray
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            ['Prueba Excel'],
            ['Funciona correctamente'],
            ['Fecha', now()->toDateTimeString()]
        ];
    }
}