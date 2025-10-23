<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DualProject extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'dual_projects';
    protected $fillable = [
        'id',
        'has_report',
        'id_institution',
        'number_student',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    public function organizationDualProjects()
    {
        return $this->hasOne(OrganizationDualProject::class, 'id_dual_project');
    }

    public function dualProjectStudents()
    {
        return $this->hasMany(DualProjectStudent::class, 'id_dual_project');
    }

    public function dualProjectReports()
    {
        return $this->hasOne(DualProjectReport::class, 'dual_project_id');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['has_report', 'id_institution', 'number_student'])
            ->logOnlyDirty()
            ->useLogName('dual_project');
    }
}
