<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Organization extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'organizations';
    protected $fillable = [
        'id',
        'name',
        'id_type',
        'id_sector',
        'size',
        'id_cluster',           // Cluster nacional
        'id_cluster_local',     // Cluster local
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
        'scope',
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

    public function clusterLocal()
    {
        return $this->belongsTo(Cluster::class, 'id_cluster_local');
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'id_type',
                'id_sector',
                'size',
                'id_cluster',
                'id_cluster_local',
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
                'scope',
            ])
            ->logOnlyDirty()
            ->useLogName('organization');
    }
}
