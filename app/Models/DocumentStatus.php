<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DocumentStatus extends Model
{
    use LogsActivity, SoftDeletes, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'document_statuses';
    protected $fillable = [
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
