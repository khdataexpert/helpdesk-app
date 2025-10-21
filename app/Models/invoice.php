<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'total',
        'status',
        'client_id',
        'project_id',
        'created_by',
        'attachment',
        'company_id',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total' => 'decimal:2',
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
