<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualType extends Model
{
    use SoftDeletes;
    protected $table = 'dual_types';
    protected $fillable = [
        'id',
        'name',
    ];

    public function dualProjectReport()
    {
        return $this->hasMany(DualProjectReport::class, 'dual_type_id');
    }
}
