<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'order', 'project_id'];

        // Define the relationship with Project
        public function project()
        {
            return $this->belongsTo(Project::class);
        }
}
