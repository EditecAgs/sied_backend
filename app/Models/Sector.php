<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;  

class Sector extends Model
{
    use LogsActivity;
    use softDeletes;

    protected $table = 'sectors';
    protected $fillable = [
        'id',
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
