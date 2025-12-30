<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('specialization');
            $table->text('profile_text');
            $table->decimal('rating', 2, 1)->default(0); // Assuming 2 digits and 1 decimal place for rating
            $table->boolean('active')->default(true); // To indicate if the doctor is active
            $table->timestamps();
            $table->timestamp('last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors'); 
    }
};
