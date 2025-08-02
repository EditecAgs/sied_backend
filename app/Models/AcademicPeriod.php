<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicPeriod extends Model
{
    use SoftDeletes;

    protected $table = 'academic_periods';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_academic_period');
    }
}
