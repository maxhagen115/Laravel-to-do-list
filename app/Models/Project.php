<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'beschrijving',
        'image',
        'is_done',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
