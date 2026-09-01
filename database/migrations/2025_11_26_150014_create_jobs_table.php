<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('advertiser_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('position')->nullable();
            $table->longText('description')->nullable();
            $table->string('language')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('work_hours')->nullable();
            $table->string('salary_currency')->nullable();
            $table->string('salary_period')->nullable();
            $table->string('job_type')->nullable();
            $table->string('status')->nullable();
            $table->decimal('sell_price', 10, 2)->nullable();
            $table->string('sell_price_currency')->nullable();
            $table->string('revenue_type')->nullable();
            $table->decimal('salary_minimum', 10, 2)->nullable();
            $table->decimal('salary_maximum', 10, 2)->nullable();
            $table->string('application_url')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
