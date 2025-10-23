<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class DualProjectReport extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'dual_project_id',
        'id_dual_area',
        'dual_type_id',
        'period_start',
        'period_end',
        'status_document',
        'economic_support',
        'amount',
        'qualification',
        'advisor',
        'is_concluded',
        'is_hired',
        'max_qualification',
        'description',
        'period_observation',
        'hired_observation'
    ];

    public function microCredentials()
    {
        return $this->belongsToMany(
            MicroCredential::class,
            'dual_project_report_micro_credential',
            'id_dual_project_report',
            'id_micro_credential'
        )->withTimestamps()->withTrashed();
    }

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

    public function dualType()
    {
        return $this->belongsTo(DualType::class, 'dual_type_id');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'dual_project_id',
                'id_dual_area',
                'dual_type_id',
                'period_start',
                'period_end',
                'status_document',
                'economic_support',
                'amount',
                'qualification',
                'advisor',
                'is_concluded',
                'is_hired',
                'max_qualification',
                'description',
                'period_observation',
                'hired_observation'
            ])
            ->logOnlyDirty()
            ->useLogName('dual_project_report');
    }
}
