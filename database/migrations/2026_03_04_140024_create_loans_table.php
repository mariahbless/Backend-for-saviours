<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Personal Information
            $table->string('name');
            $table->string('email');
            $table->string('contact');
            $table->string('other_contact')->nullable();
            $table->string('gender');
            $table->string('location');
            $table->text('current_address');
            $table->string('occupation');
            $table->decimal('monthly_income', 12, 2);

            // Next of Kin
            $table->string('next_of_kin_name');
            $table->string('next_of_kin_contact');

            // Loan Details
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable(); // loan type
            $table->text('collateral')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected

            // ID Upload (stored as file path)
            $table->string('id_image')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};