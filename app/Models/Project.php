<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'thumbnail', 'description', 'user_id', 'tags', 'price'];

    public function users_liked()
    {
        return $this->belongsToMany(User::class, 'users-liked', 'project_id', 'user_id');
    }
    public function users_saved()
    {
        return $this->belongsToMany(User::class, 'users-saved', 'project_id', 'user_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id','avatar','name');
    }

}
