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
        'id_image_front',
        'id_image_back',

        'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'monthly_income' => 'float',
    ];

    protected static function booted()
    {
        static::created(function ($loan) {
            \App\Services\ActivityLogger::log(
                'Created Loan',
                "Loan #{$loan->id} of UGX ".number_format($loan->amount)." created for applicant {$loan->name}."
            );
        });

        static::updated(function ($loan) {
            if ($loan->isDirty('status')) {
                $status = ucfirst($loan->status);
                \App\Services\ActivityLogger::log(
                    "{$status} Loan",
                    "Loan #{$loan->id} for applicant {$loan->name} was {$loan->status}."
                );
            } else {
                \App\Services\ActivityLogger::log(
                    'Updated Loan',
                    "Loan #{$loan->id} details updated for applicant {$loan->name}."
                );
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function guarantors()
    {
        return $this->hasMany(\App\Models\Guarantor::class);
    }
}
