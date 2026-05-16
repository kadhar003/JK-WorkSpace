<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectFile;

class Project extends Model
{
    protected $guarded = [];

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }
}
