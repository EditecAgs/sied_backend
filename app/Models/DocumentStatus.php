<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DocumentStatus extends Model
{
    use LogsActivity;
    use softDeletes;
    protected $table = 'document_statuses';
    protected $fillable = [
        'id',
        'name',
    ];

    public function dualProjectReports()
    {
        return $this->hasMany(DualProjectReport::class, 'status_document');
    }
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->useLogName('document_status');
    }
}
