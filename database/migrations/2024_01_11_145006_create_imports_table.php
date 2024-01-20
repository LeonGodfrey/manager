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
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('import_file')->nullable();
            $table->string('status')->nullable();
            $table->text('message')->nullable();
            $table->string('error_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
