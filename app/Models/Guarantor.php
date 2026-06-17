<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guarantor extends Model
{
    protected $fillable = [
        'loan_id',
        'name',
        'email',
        'phone',
        'relationship',
        'location',
        'occupation',
        'id_number',
        'id_image_front',
        'id_image_back',
    ];

    protected static function booted()
    {
        static::created(function ($guarantor) {
            \App\Services\ActivityLogger::log(
                'Added Guarantor',
                "Guarantor {$guarantor->name} was added for Loan #{$guarantor->loan_id}."
            );
        });

        static::updated(function ($guarantor) {
            \App\Services\ActivityLogger::log(
                'Updated Guarantor',
                "Guarantor {$guarantor->name} details for Loan #{$guarantor->loan_id} were updated."
            );
        });

        static::deleted(function ($guarantor) {
            \App\Services\ActivityLogger::log(
                'Removed Guarantor',
                "Guarantor {$guarantor->name} was removed from Loan #{$guarantor->loan_id}."
            );
        });
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
