<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class State extends Model
{
    use LogsActivity;
    protected $table = 'states';

    protected $fillable = [
        'id',
        'name',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_state');
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'id_state');
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_state');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])       
            ->logOnlyDirty()          
            ->useLogName('state');    
    }
}
