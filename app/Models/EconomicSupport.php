<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EconomicSupport extends Model
{
    use LogsActivity;
    use softDeletes;
    protected $table = 'economic_supports';
    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function dualProjectReports()
    {
        return $this->hasMany(DualProjectReport::class, 'economic_support');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description'])
            ->logOnlyDirty()
            ->useLogName('economic_support');
    }
}
