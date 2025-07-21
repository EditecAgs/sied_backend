<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualArea extends Model
{
    use SoftDeletes;
    protected $table = 'dual_areas';
    protected $fillable = [
        'name',
    ];

    public function dualProjects()
    {
        return $this->hasMany(DualProject::class, 'id_dual_area');
    }
}
