<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Student extends Model
{
    use LogsActivity;
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

    public function dualProjectStudents()
    {
        return $this->hasMany(DualProjectStudent::class, 'id_student');
    }
    
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['control_number', 'name', 'lastname', 'gender', 'semester', 'id_institution', 'id_specialty', 'id_career', 'id_dual_project'])
            ->logOnlyDirty()
            ->useLogName('student');
}
}