<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cluster extends Model
{
    use SoftDeletes;
    protected $table = 'clusters';
    protected $fillable = [
        'name',
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_cluster');
    }
}
