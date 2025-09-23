<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProject extends Model
{
    use SoftDeletes;
    protected $table = 'dual_projects';
    protected $fillable = [
        'id',
        'has_report',
        'id_institution',
        'number_student'
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
}
