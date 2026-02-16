<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProjectReportCertification extends Model
{
    use SoftDeletes;

    protected $table = 'dual_project_report_certification';

    protected $fillable = [
        'id',
        'id_certification',
        'id_dual_project_report',
    ];

    public function certification()
    {
        return $this->belongsTo(Certification::class, 'id_certification');
    }

    public function dualProjectReport()
    {
        return $this->belongsTo(DualProjectReport::class, 'id_dual_project_report');
    }
}
