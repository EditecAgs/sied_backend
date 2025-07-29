<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $table = 'students';
    protected $fillable = [
        'id',
        'control_number',
        'name',
        'lastname',
        'gender',
        'semester',
        'id_institution',
        'id_specialty',
        'id_career',
        'id_dual_project',
    ];

    protected $casts = [
        'gender' => 'string',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'id_specialty');
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'id_career');
    }

    public function dualProject()
    {
        return $this->belongsTo(DualProject::class, 'id_dual_project');
    }
}
