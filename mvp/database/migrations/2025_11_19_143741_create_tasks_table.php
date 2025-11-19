<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('budget')->nullable();

            // 繰り返しタイプ
            $table->string('repeat_type')->nullable(); // '', daily, weekly, monthly

            // 単発タスク用
            $table->date('date')->nullable();

            // 繰り返し期間用
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // weekly 用
            $table->json('day_of_week')->nullable(); // ["Mon","Wed"]

            // monthly 用
            $table->string('monthly_type')->nullable();      // date / weekday
            $table->tinyInteger('monthly_date')->nullable(); // 日付指定
            $table->string('monthly_weekday')->nullable();   // Mon〜Sun
            $table->tinyInteger('monthly_week_num')->nullable(); // 第何週

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
