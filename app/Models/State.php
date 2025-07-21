<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    protected $fillable = [
        'name',
    ];

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_state');
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'id_state');
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_state');
    }
}
