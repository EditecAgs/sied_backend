<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use SoftDeletes;

    protected $table = 'institutions';

    protected $fillable = [
        'id',
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
        'image',
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

    public function careers()
    {
        return $this->hasMany(Career::class, 'id_institution');
    }

    public function specialties()
    {
        return $this->hasMany(Specialty::class, 'id_institution');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_institution');
    }
}
