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
        Schema::create('notification', function (Blueprint $table) {
            $table->id()->startingValue(100000);
            $table->bigInteger('from_id')->unsigned();
            $table->bigInteger('to_id')->unsigned();
            $table->string('title');
            $table->text('message');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->enum('type', ['info', 'warning', 'danger', 'success'])->default('info');
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
