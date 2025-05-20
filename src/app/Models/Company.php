<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'legal_name',
        'tax_id',
        'trade_register',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'logo_path',
        'is_main',
        'parent_company_id'
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    // Relations
    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    public function subsidiaries()
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
