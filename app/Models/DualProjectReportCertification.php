<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DualProjectReportCertification extends Model
{
    use SoftDeletes, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'dual_project_report_certification';

    protected $fillable = [
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
