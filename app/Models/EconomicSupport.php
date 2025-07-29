<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicSupport extends Model
{
    use softDeletes;
    protected $table = 'economic_supports';
    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function dualProjectReports()
    {
        return $this->hasMany(DualProjectReport::class, 'economic_support');
    }
}
