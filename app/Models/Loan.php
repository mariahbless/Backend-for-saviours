<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
    'user_id',
    'amount',
    'description',
    'status',
];
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}