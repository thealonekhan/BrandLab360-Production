<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManagement extends Model
{
    use HasFactory;

    protected $table = 'project_managment';

    /**
     * Get the Projects that owns the Managment.
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id')->withTrashed();
    }

    /**
     * Get the Users that owns the Managment.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }
}
