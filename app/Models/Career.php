<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;
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
}
