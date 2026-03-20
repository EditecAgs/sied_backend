<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectDraft extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'project_id',
        'form_data',
        'reporta_modelo_dual',
        'section1_expanded',
        'section2_expanded',
        'section3_expanded',
    ];

    protected $casts = [
        'form_data' => 'array',
        'reporta_modelo_dual' => 'boolean',
        'section1_expanded' => 'boolean',
        'section2_expanded' => 'boolean',
        'section3_expanded' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(DualProject::class, 'project_id');
    }
}
