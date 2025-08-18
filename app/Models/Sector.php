<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use softDeletes;

    protected $table = 'sectors';
    protected $fillable = [
        'id',
        'name',
        'plan_mexico',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_sector');
    }
}
