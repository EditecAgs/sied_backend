<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProjectReport extends Model
{
    use SoftDeletes;
    protected $table = 'dual_project_reports';
    protected $fillable = [
        'id',
        'dual_project_id',
        'name',
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

    public function dualProject()
    {
        return $this->belongsTo(DualProject::class, 'dual_project_id');
    }
}
