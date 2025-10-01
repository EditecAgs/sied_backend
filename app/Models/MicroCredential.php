<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroCredential extends Model
{
    use SoftDeletes;
    protected $table = 'micro_credentials';
    protected $fillable = [
        'id',
        'name',
        'organization',
        'description',
        'image',
    ];

    public function dualProjectReportMicroCredentials()
    {
        return $this->hasMany(DualProjectReportMicroCredential::class, 'id_micro_credential');
    }

    public function dualProjectReports()
    {
        return $this->belongsToMany(
            DualProjectReport::class,
            'dual_project_report_micro_credential',
            'id_micro_credential',
            'id_dual_project_report'
        )->withTimestamps()->withTrashed();
    }
}
