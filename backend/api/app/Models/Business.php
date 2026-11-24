<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'phone',
        'email',
        'gstin',
        'business_pan',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'timezone',
        'status',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
            ->withPivot(['role', 'status', 'joined_at'])
            ->withTimestamps();
    }
}
