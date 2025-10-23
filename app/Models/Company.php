<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'image'];
    
    public function style()
    {
        return $this->hasOne(CompanyStyle::class);
    }
}
