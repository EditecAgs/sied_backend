<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AcademicPeriod extends Model
{
    use LogsActivity, SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'academic_periods';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_academic_period');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description'])
            ->logOnlyDirty()
            ->useLogName('academic_period');
    }
}
