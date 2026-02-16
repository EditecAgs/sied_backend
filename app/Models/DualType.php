<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DualType extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'dual_types';
    protected $fillable = [
        'id',
        'name',
    ];

    public function dualProjectReport()
    {
        return $this->hasMany(DualProjectReport::class, 'dual_type_id');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('dual_type');
    }
}
