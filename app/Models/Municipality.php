<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Municipality extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'municipalities';

    protected $fillable = [
        'name',
        'id_state',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    public function institutions()
    {
        return $this->hasMany(Institution::class, 'id_municipality');
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'id_municipality');
    }
}
