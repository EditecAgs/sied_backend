<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Type extends Model
{
    use LogsActivity;
    use softDeletes;

    protected $table = 'types';
    protected $fillable = [
        'id',
        'name',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_type');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('type');
    }
}
