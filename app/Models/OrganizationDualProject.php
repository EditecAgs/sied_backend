<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrganizationDualProject extends Model
{
    use SoftDeletes, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'organizations_dual_projects';
    protected $fillable = [
        'id_organization',
        'id_dual_project',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'id_organization');
    }

    public function dualProject()
    {
        return $this->belongsTo(DualProject::class, 'id_dual_project');
    }
}
