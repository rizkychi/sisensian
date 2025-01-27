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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('office_id')->unsigned()->nullable();
            $table->bigInteger('schedule_id')->unsigned()->nullable();
            $table->date('date');
            $table->time('time_in');
            $table->time('time_out');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('check_in_lat')->nullable();
            $table->string('check_in_long')->nullable();
            $table->string('check_in_address')->nullable();
            $table->string('check_out_lat')->nullable();
            $table->string('check_out_long')->nullable();
            $table->string('check_out_address')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_on_leave')->default(false);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('office_id')->references('id')->on('office')->onDelete('set null');
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
