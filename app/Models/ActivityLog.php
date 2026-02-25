<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class ActivityLog extends SpatieActivity
{
    use HasUuids;

    /**
     * La tabla asociada.
     */
    protected $table = 'activity_log';

    /**
     * Desactivar auto-increment y usar string como PK.
     */
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Los campos asignables en masa.
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
        'event',
        'batch_uuid',
    ];

    /**
     * Asignar UUID automáticamente al crear un registro.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
