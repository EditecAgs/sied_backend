<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DualProjectStudent extends Model
{
    use SoftDeletes;
    protected $table = 'dual_project_students';
    protected $fillable = [
        'id',
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
