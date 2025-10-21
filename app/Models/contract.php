<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'notes',
        'client_id',
        'project_id',
        'created_by',
        'start_date',
        'end_date',
        'status',
        'attachment',
        'company_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
