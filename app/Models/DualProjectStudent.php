<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DualProjectStudent extends Model
{
    use SoftDeletes, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'dual_project_students';
    protected $fillable = [
        'id_student',
        'id_dual_project',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function dualProject()
    {
        return $this->belongsTo(DualProject::class, 'id_dual_project');
    }
}
