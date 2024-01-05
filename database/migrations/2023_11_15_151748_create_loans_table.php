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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_product_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('org_id')->nullable();
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('loan_officer_id')->nullable();
            $table->foreign('loan_officer_id')->references('id')->on('users');
            $table->unsignedBigInteger('approval_officer_id')->nullable();
            $table->foreign('approval_officer_id')->references('id')->on('users');
            $table->unsignedBigInteger('disbursement_officer_id')->nullable();
            $table->foreign('disbursement_officer_id')->references('id')->on('users');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->constrained()->onDelete('cascade');
            $table->decimal('application_amount', 15, 2);
            $table->text('purpose')->nullable();
            $table->date('application_date');
            $table->integer('application_period');
            $table->decimal('appraisal_amount', 15, 2)->nullable();
            $table->integer('appraisal_period')->nullable();
            $table->date('appraisal_date')->nullable();
            $table->text('appraisal_comment')->nullable();
            $table->string('file_link1')->nullable();
            $table->string('file_link2')->nullable();
            $table->string('file_link3')->nullable();
            $table->string('file_link4')->nullable();
            $table->string('file_link5')->nullable();
            $table->string('file_link6')->nullable();
            $table->string('file_link7')->nullable();
            $table->string('file_link8')->nullable();
            $table->decimal('approved_amount', 15, 2)->default(0);
            $table->integer('approved_period')->nullable();
            $table->date('approved_date')->nullable();
            $table->float('approved_interest_rate')->default(0);
            $table->text('approved_comment')->nullable();
            $table->text('defer_comment')->nullable();
            $table->date('disbursement_date')->nullable();
            $table->string('voucher_number')->nullable();  
            $table->decimal('paid_principal', 15, 2)->default(0);
            $table->decimal('paid_interest', 15, 2)->default(0);
            $table->decimal('paid_penalties', 15, 2)->default(0);        
            $table->enum('status', ['pending_appraisal', 'pending_approval', 'approved', 'disbursed', 'cleared', 'deferred', 'waived_off', 'written_off'])->default('pending_appraisal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
