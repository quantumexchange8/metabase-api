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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('phone')->unique();
            $table->integer('trade_min_experience');
            $table->integer('trade_max_experience');
            $table->string('source_of_fund');
            $table->string('gender');
            $table->string('dob');
            $table->string('nationality');
            $table->tinyInteger('us_citizen');
            $table->string('identification_type');
            $table->string('identification_number')->unique();
            $table->string('referral_code')->nullable();
            $table->string('upline_id')->nullable();
            $table->string('hierarchyList')->nullable();
            $table->string('kyc_approval')->default('pending');
            $table->text('kyc_approval_description')->nullable();
            $table->string('status', 20)->default('active');
            $table->string('role')->default('user');
            $table->string('user_type')->default('user');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
