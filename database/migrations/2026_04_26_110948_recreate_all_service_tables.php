<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Drop tables if they exist (in correct order)
        if (Schema::hasTable('service_types')) {
            Schema::drop('service_types');
        }
        if (Schema::hasTable('service_categories')) {
            Schema::drop('service_categories');
        }
        
        // Create service_categories table
        Schema::create('service_categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('category_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Create service_types table
        Schema::create('service_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('category_id');
            $table->decimal('price_per_load', 10, 2);
            $table->timestamps();
            
            $table->foreign('category_id')
                  ->references('id')
                  ->on('service_categories')
                  ->onDelete('cascade');
        });
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('service_types');
        Schema::dropIfExists('service_categories');
        
        Schema::enableForeignKeyConstraints();
    }
};