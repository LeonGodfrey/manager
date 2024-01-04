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
        Schema::create('savings_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('savings_product_id');
            $table->unsignedBigInteger('branch_id');
            $table->date('opened_at');//added
            $table->date('last_transacted_at');//added
            $table->string('account_name')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->constrained()->onDelete('cascade');
            $table->foreign('savings_product_id')->references('id')->on('savings_products')->constrained()->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_accounts', function (Blueprint $table) {
            Schema::dropIfExists('savings_accounts');
        });
    }
};
