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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('org_name');
            $table->string('org_country');
            $table->string('currency_code');
            $table->date('incorporation_date')->nullable();
            $table->string('business_reg_no')->nullable();
            $table->string('manager_name');
            $table->string('manager_contact')->nullable();
            $table->string('org_logo')->nullable(); // Assuming org_logo is a file path, set as nullable
            // Add any other fields here
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
