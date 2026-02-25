<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DualProjectReportMicroCredential extends Model
{
    use SoftDeletes, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'dual_project_report_micro_credential';

    protected $fillable = [
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
