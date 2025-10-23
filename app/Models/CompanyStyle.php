<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStyle extends Model
{
    protected $fillable = [
        'company_id',
        'primary_color',
        'secondary_color',
        'background_color',
        'text_color',
    ];

        public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
