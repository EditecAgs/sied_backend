<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Cluster extends Model
{
    use LogsActivity, SoftDeletes, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'clusters';
    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_cluster');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'type'])
            ->logOnlyDirty()
            ->useLogName('cluster');
    }
}
