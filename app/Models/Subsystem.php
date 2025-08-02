<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsystem extends Model
{
    use SoftDeletes;

    protected $table = 'subsystems';

    protected $fillable = [
        'id',
        'name',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_subsystem');
    }
}
