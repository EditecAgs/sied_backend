<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subsystem extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'subsystems';

    protected $fillable = [
        'id',
        'name',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_subsystem');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('subsystem');
    }
}
