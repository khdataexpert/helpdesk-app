<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'Specialization', 'user_id'];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function lead()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
