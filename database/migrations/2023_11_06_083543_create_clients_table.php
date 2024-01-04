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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->constrained()->onDelete('cascade');
            $table->string('client_number')->unique();
            $table->string('ipps_number')->nullable();
            $table->string('surname');
            $table->string('given_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('dob');
            $table->date('registration_date');//added
            $table->string('phone');
            $table->string('alt_phone')->nullable();
            $table->string('email_address')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('county')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('parish')->nullable();
            $table->string('village')->nullable();
            $table->string('home_district')->nullable();
            $table->string('home_village')->nullable();
            $table->string('kin_name');
            $table->string('kin_phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('id_photo')->nullable();
            $table->string('id_number')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('signature')->nullable();
            $table->string('other_file')->nullable();
            $table->enum('status', ['Active', 'Dead', 'Inactive'])->default('Active');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
