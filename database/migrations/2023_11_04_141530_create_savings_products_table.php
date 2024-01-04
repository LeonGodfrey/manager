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
        Schema::create('savings_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->enum('saving_product_type', ['standard', 'fixed']);
            $table->string('name');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('min_balance', 15, 2)->default(0);
            $table->decimal('deposit_fee', 15, 2)->default(0);
            $table->decimal('withdrawal_fee', 15, 2)->default(0);
            $table->decimal('monthly_fee', 15, 22)->default(0);
            $table->float('interest_rate')->default(0);
            $table->float('penalty_rate')->default(0);
            $table->enum('interest_frequency', ['Daily', 'Weekly', 'Monthly', 'Quarterly', 'Annually'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_products');
    }
};
