<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $guarded = [];

    protected $fillable = [

        'project_id',
        'name',
        'path',
        'type',
        'content'

    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}