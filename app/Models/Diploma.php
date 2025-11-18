<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Diploma extends Model
{

    use LogsActivity;
    use SoftDeletes;

    protected $table = 'diplomas';

    protected $fillable = [
        'id',
        'name',
        'organization',
        'description',
        'image',
        'type',
    ];

    public function dualProjectReportDiplomas()
    {
        return $this->hasMany(DualProjectReportDiploma::class, 'id_diploma');
    }

    public function dualProjectReports()
    {
        return $this->belongsToMany(
            DualProjectReport::class,
            'dual_project_report_diploma',
            'id_diploma',
            'id_dual_project_report'
        )->withTimestamps()->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'organization', 'description', 'image'])
            ->logOnlyDirty()
            ->useLogName('diploma');
    }
}
