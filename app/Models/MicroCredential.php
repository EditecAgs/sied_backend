<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MicroCredential extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'micro_credentials';
    protected $fillable = [
        'id',
        'name',
        'organization',
        'description',
        'image',
        'type',
        'hours',
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
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'organization', 'description', 'image', 'type', 'hours'])
            ->logOnlyDirty()
            ->useLogName('micro_credential');
    }
}
