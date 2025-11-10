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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('budget')->nullable();
            $table->date('date');
            $table->text('repeat_type')->nullable();    // 繰り返しタイプ（例：daily, weekly）
            $table->text('day_of_week')->nullable();    // 曜日（例："Mon,Wed,Fri"）
            $table->date('start_date')->nullable();     // 繰り返し開始日
            $table->date('end_date')->nullable();       // 繰り返し終了日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
