<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BenefitType extends Model
{
    use LogsActivity, SoftDeletes;
    
    protected $table = 'benefit_types';

    protected $fillable = [
        'name',
    ];

    public function dualProjectReportBenefitTypes()
    {
        return $this->hasMany(
            BenefitTypeDualProjectReport::class,
            'id_benefit_type'
        );
    }

    public function dualProjectReports()
    {
        return $this->belongsToMany(
            DualProjectReport::class,
            'benefit_type_dual_project_report',
            'id_benefit_type',
            'id_dual_project_report'
        )
        ->withPivot('quantity')
        ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('benefit_type');
    }
}
