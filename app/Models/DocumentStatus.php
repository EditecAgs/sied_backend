<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentStatus extends Model
{
    use softDeletes;
    protected $table = 'document_statuses';
    protected $fillable = [
        'name',
    ];

    public function dualProjects()
    {
        return $this->hasMany(DualProject::class, 'status_document');
    }
}
