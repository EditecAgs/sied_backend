<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DualProjectReportDiploma extends Model
{
    use SoftDeletes, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'dual_project_report_diploma';

    protected $fillable = [
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
