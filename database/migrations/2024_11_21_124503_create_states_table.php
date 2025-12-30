<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id('id_state'); // Primary key with custom name
            $table->string('state', 100); // State name
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade'); // Foreign key to countries table
            $table->integer('is_active')->nullable(); // Status (e.g., active/inactive)
            $table->integer('sort_order')->nullable(); // For sorting purposes
            $table->string('lang')->nullable(); // Language field
            $table->timestamps(); // Created at and updated at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
