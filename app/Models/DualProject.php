<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProject extends Model
{
    use SoftDeletes;
    protected $table = 'dual_projects';
    protected $fillable = [
        'name',
        'has_report',
        'number_men',
        'number_women',
        'id_dual_area',
        'period_start',
        'period_end',
        'status_document',
        'economic_support',
        'amount',
    ];

    public function dualArea()
    {
        return $this->belongsTo(DualArea::class, 'id_dual_area');
    }

    public function statusDocument()
    {
        return $this->belongsTo(DocumentStatus::class, 'status_document');
    }

    public function economicSupport()
    {
        return $this->belongsTo(EconomicSupport::class, 'economic_support');
    }

    public function organizationDualProjects()
    {
        return $this->hasMany(OrganizationDualProject::class, 'id_dual_project');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_dual_project');
    }
}
