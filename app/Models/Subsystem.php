<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsystem extends Model
{
    protected $table = 'subsystems';

    protected $fillable = [
        'name',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_subsystem');
    }
}
