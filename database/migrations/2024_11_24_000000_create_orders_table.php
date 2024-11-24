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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('participant_id') // Foreign key for the participant
                  ->constrained('participants')
                  ->cascadeOnDelete(); // Deletes order if participant is deleted
            $table->foreignId('product_id') // Foreign key for the product
                  ->constrained('products')
                  ->cascadeOnDelete(); // Deletes order if product is deleted
            $table->integer('quantity'); // Quantity of the product in the order
            $table->decimal('total', 10, 2); // Total cost of the order
            $table->string('state')->default('pending'); // State of the order
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
