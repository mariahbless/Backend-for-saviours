<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',

        // Personal Information
        'name',
        'email',
        'contact',
        'other_contact',
        'gender',
        'location',
        'current_address',
        'occupation',
        'monthly_income',

        // Next of Kin
        'next_of_kin_name',
        'next_of_kin_contact',

        // Loan Details
        'amount',
        'description',
        'collateral',
        'status',

        // ID Upload
        'id_image',

        'notes',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}