<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    protected $table = 'institutions';

    protected $fillable = [
        'name',
        'street',
        'external_number',
        'internal_number',
        'neighborhood',
        'postal_code',
        'id_municipality',
        'id_state',
        'country',
        'city',
        'google_maps',
        'type',
        'id_subsystem',
        'id_academic_period',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'id_municipality');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    public function subsystem()
    {
        return $this->belongsTo(Subsystem::class, 'id_subsystem');
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class, 'id_academic_period');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_institution');
    }
}
