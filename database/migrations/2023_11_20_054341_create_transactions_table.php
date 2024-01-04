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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('savings_account_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();            
            $table->date('date');
            $table->string('type');
            $table->string('subtype')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('receipt_number')->nullable();
            $table->string('particular')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_reversed')->default(0);
            $table->text('reversal_reason')->nullable();

            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->foreign('savings_account_id')->references('id')->on('savings_accounts');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
