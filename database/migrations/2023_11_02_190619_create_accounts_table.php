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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number')->nullable();
            $table->decimal('balance', 15, 2)->default(0.00); // Adjust the precision and scale as needed    
            $table->enum('type', ['Asset', 'Equity', 'Expense', 'Income', 'Liability']);
            $table->string('subtype')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active'); 
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('savings_product_id')->nullable();
            $table->foreign('savings_product_id')->references('id')->on('branches')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('loan_product_id')->nullable();
            $table->foreign('loan_product_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
