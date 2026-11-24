<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();

            $table->string('business_type', 50);
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();

            $table->string('gstin', 15)->nullable();
            $table->string('business_pan', 10)->nullable();

            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('pincode', 10)->nullable();

            $table->string('country', 2)->default('IN');
            $table->string('timezone', 50)->default('Asia/Kolkata');

            $table->string('status', 20)->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};