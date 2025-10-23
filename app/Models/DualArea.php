<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class DualArea extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'dual_areas';
    protected $fillable = [
        'id',
        'name',
    ];

    public function dualProjectReports()
    {
        return $this->hasMany(DualProjectReport::class, 'id_dual_area');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('dual_area');
    }
}
