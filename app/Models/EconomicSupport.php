<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicSupport extends Model
{
    use softDeletes;
    protected $table = 'economic_supports';
    protected $fillable = [
        'name',
        'description',
    ];

    public function dualProjects()
    {
        return $this->hasMany(DualProject::class, 'economic_support');
    }
}
