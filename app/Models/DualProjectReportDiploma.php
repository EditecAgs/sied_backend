<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProjectReportDiploma extends Model
{
    use SoftDeletes;

    protected $table = 'dual_project_report_diploma';

    protected $fillable = [
        'id',
        'id_diploma',
        'id_dual_project_report',
    ];

    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'id_diploma');
    }

    public function dualProjectReport()
    {
        return $this->belongsTo(DualProjectReport::class, 'id_dual_project_report');
    }
}
