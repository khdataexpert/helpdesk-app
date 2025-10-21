<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $fillable = ['name', 'description', 'client_id', 'team_id', 'status', 'assigned_to', 'company_id'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
