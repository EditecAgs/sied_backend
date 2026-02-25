<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Certification extends Model
{
    use LogsActivity, SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'certifications';

    protected $fillable = [
        'id',
        'name',
        'organization',
        'description',
        'image',
        'type',
        'hours',
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
            ->logOnly(['name', 'organization', 'description', 'image', 'hours'])
            ->logOnlyDirty()
            ->useLogName('certification');
    }
}
