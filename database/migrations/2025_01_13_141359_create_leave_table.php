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
        Schema::create('leave', function (Blueprint $table) {
            $table->id()->startingValue(100000);
            $table->bigInteger('employee_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('leave_type');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->bigInteger('confirmed_by')->unsigned()->nullable();
            $table->date('confirmed_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave');
    }
};
