<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear old description data that doesn't match enum values
        DB::table('loans')->truncate();

        Schema::table('loans', function (Blueprint $table) {
            $table->enum('description', [
                'School Fees Loan',
                'Business Loan',
                'Personal Loan',
                'Land Title Loan'
            ])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }
};