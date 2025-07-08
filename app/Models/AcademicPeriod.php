<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $table = 'academic_periods';

    protected $fillable = [
        'name',
        'description',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_academic_period');
    }
}
