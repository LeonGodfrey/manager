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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');            
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('interest_method', ['Declining_balance', 'Flat']);
            $table->float('interest_rate')->default(0);
            $table->enum('payment_frequency', ['Daily', 'Weekly', 'Monthly', 'Quarterly', 'Annually']);
            $table->float('penalty_rate')->default(0);
            $table->integer('grace_period')->default(0);
            $table->boolean('charge_interest_grace_period')->default(0);
            $table->integer('arrears_maturity_period')->default(0);
            $table->integer('max_loan_period')->default(0);
            //max loan amount
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
