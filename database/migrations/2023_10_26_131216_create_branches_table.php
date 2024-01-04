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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->string('branch_name');
            $table->string('branch_phone');
            $table->string('branch_email');
            $table->string('branch_prefix');
            $table->string('branch_street_address');
            $table->string('branch_city');
            $table->string('branch_district');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('branch_postcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
