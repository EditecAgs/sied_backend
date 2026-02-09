<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Career extends Model
{
    use SoftDeletes, LogsActivity, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'careers';

    protected $fillable = [
        'id',
        'name',
        'id_institution',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    public function specialties()
    {
        return $this->hasMany(Specialty::class, 'id_career');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_career');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'id_institution'])
            ->logOnlyDirty()
            ->useLogName('career');
    }
}
