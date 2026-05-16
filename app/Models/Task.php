<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Workspace;

class Task extends Model
{
    protected $guarded = [];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function assignee(){
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
