<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use SoftDeletes;
    protected $table = 'specialties';
    protected $fillable = [
        'id',
        'name',
        'id_institution',
        'id_career',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'id_career');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_specialty');
    }
}
