<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    /**
     * Get the User that owns the Notes.
     */
    public function project_managment()
    {
        return $this->belongsTo('App\Models\ProjectManagment', 'project_id')->withTrashed();
    }

    /**
     * Get the User that created this project.
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'created_by')->withTrashed();
    }

    /**
     * Get the Status that owns the Projects.
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }
}
