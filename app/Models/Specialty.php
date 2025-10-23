<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Specialty extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'specialties';
    protected $fillable = [
        'id',
        'name',
        'id_institution',
        'id_career',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    public function career()
    {
        return $this->belongsTo(Career::class, 'id_career');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_specialty');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'id_institution', 'id_career'])
            ->logOnlyDirty()
            ->useLogName('specialty');
    }
}
