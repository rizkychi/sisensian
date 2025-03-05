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
        Schema::create('employee', function (Blueprint $table) {
            $table->id()->startingValue(100000);
            $table->bigInteger('user_id')->unsigned();
            $table->string('id_number')->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->bigInteger('office_id')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->string('photo')->nullable();
            $table->enum('category', ['regular', 'shift'])->default('regular');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('office_id')->references('id')->on('office');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
