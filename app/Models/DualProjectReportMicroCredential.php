<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProjectReportMicroCredential extends Model
{
    use SoftDeletes;

    protected $table = 'dual_project_report_micro_credential';

    protected $fillable = [
        'id',
        'id_micro_credential',
        'id_dual_project_report',
    ];

    public function microCredential()
    {
        return $this->belongsTo(MicroCredential::class, 'id_micro_credential');
    }

    public function dualProjectReport()
    {
        return $this->belongsTo(DualProjectReport::class, 'id_dual_project_report');
    }
}
