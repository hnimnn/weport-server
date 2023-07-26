<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'thumbnail', 'description', 'user_id', 'tags'];

     public function users_liked()
    {
        return $this->belongsToMany(User::class, 'users-liked', 'project_id', 'user_id');
    }

}
