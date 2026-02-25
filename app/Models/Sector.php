<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;  
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sector extends Model
{
    use LogsActivity, SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';


    protected $table = 'sectors';
    protected $fillable = [
        'name',
        'plan_mexico',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_sector');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'plan_mexico'])
            ->logOnlyDirty()
            ->useLogName('sector');
    }
}
