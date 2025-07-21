<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
    protected $table = 'oorganizations';
    protected $fillable = [
        'name',
        'id_type',
        'id_sector',
        'size',
        'id_cluster',
        'street',
        'external_number',
        'internal_number',
        'neighborhood',
        'postal_code',
        'id_state',
        'id_municipality',
        'country',
        'city',
        'google_maps',
    ];

    protected $casts = [
        'size' => 'string',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'id_type');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'id_sector');
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'id_cluster');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'id_municipality');
    }

    public function organizationDualProjects()
    {
        return $this->hasMany(OrganizationDualProject::class, 'id_organization');
    }
}
