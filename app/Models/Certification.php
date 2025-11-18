<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Certification extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'certifications';

    protected $fillable = [
        'id',
        'name',
        'organization',
        'description',
        'image',
        'type',
    ];

    public function dualProjectReportCertifications()
    {
        return $this->hasMany(DualProjectReportCertification::class, 'id_certification');
    }

    public function dualProjectReports()
    {
        return $this->belongsToMany(
            DualProjectReport::class,
            'dual_project_report_certification',
            'id_certification',
            'id_dual_project_report'
        )->withTimestamps()->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'organization', 'description', 'image'])
            ->logOnlyDirty()
            ->useLogName('certification');
    }
}
