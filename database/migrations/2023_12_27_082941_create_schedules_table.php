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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->date('date');
            $table->string('type');
            $table->decimal('principal', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('penalties', 15, 2)->nullable();
            $table->boolean('is_reversed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
