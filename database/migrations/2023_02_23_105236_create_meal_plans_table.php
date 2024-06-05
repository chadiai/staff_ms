<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_type_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('meal_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->timestamps();
        });

        DB::table('meal_plans')->insert(
            [
                ['meal_type_id' => 1, 'meal_id' => 2, 'start_date_time' => '2023-02-02 08:00:00', 'end_date_time' => '2023-02-02 08:30:00', 'created_at' => now()],
                ['meal_type_id' => 2, 'meal_id' => 4, 'start_date_time' => '2023-02-03 10:00:00', 'end_date_time' => '2023-02-03 10:30:00', 'created_at' => now()],
                ['meal_type_id' => 3, 'meal_id' => 5, 'start_date_time' => '2023-02-02 13:00:00', 'end_date_time' => '2023-02-02 13:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-02-02 19:00:00', 'end_date_time' => '2023-02-02 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 3, 'start_date_time' => '2023-04-03 19:00:00', 'end_date_time' => '2023-04-03 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 3, 'start_date_time' => '2023-04-10 19:00:00', 'end_date_time' => '2023-04-10 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 4, 'start_date_time' => '2023-04-17 19:00:00', 'end_date_time' => '2023-04-17 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 5, 'start_date_time' => '2023-04-24 19:00:00', 'end_date_time' => '2023-04-24 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-05-01 19:00:00', 'end_date_time' => '2023-05-01 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 3, 'start_date_time' => '2023-05-08 19:00:00', 'end_date_time' => '2023-05-08 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 5, 'start_date_time' => '2023-05-15 19:00:00', 'end_date_time' => '2023-05-15 20:30:00', 'created_at' => now()],
                ['meal_type_id' => 1, 'meal_id' => 2, 'start_date_time' => '2023-05-22 08:00:00', 'end_date_time' => '2023-05-22 09:30:00', 'created_at' => now()],
                ['meal_type_id' => 2, 'meal_id' => 4, 'start_date_time' => '2023-05-22 10:00:00', 'end_date_time' => '2023-05-22 11:30:00', 'created_at' => now()],
                ['meal_type_id' => 3, 'meal_id' => 5, 'start_date_time' => '2023-05-22 13:00:00', 'end_date_time' => '2023-05-22 14:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-05-22 19:00:00', 'end_date_time' => '2023-05-22 21:30:00', 'created_at' => now()],
                ['meal_type_id' => 1, 'meal_id' => 2, 'start_date_time' => '2023-05-26 08:00:00', 'end_date_time' => '2023-05-26 09:30:00', 'created_at' => now()],
                ['meal_type_id' => 2, 'meal_id' => 4, 'start_date_time' => '2023-05-26 10:00:00', 'end_date_time' => '2023-05-26 11:30:00', 'created_at' => now()],
                ['meal_type_id' => 3, 'meal_id' => 5, 'start_date_time' => '2023-05-26 13:00:00', 'end_date_time' => '2023-05-26 14:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-05-26 19:00:00', 'end_date_time' => '2023-05-26 21:30:00', 'created_at' => now()],
                ['meal_type_id' => 1, 'meal_id' => 2, 'start_date_time' => '2023-06-05 08:00:00', 'end_date_time' => '2023-06-05 09:30:00', 'created_at' => now()],
                ['meal_type_id' => 2, 'meal_id' => 4, 'start_date_time' => '2023-06-05 10:00:00', 'end_date_time' => '2023-06-05 11:30:00', 'created_at' => now()],
                ['meal_type_id' => 3, 'meal_id' => 5, 'start_date_time' => '2023-06-05 13:00:00', 'end_date_time' => '2023-06-05 14:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-06-05 19:00:00', 'end_date_time' => '2023-06-05 21:30:00', 'created_at' => now()],
                ['meal_type_id' => 1, 'meal_id' => 2, 'start_date_time' => '2023-06-09 08:00:00', 'end_date_time' => '2023-06-09 09:30:00', 'created_at' => now()],
                ['meal_type_id' => 2, 'meal_id' => 4, 'start_date_time' => '2023-06-09 10:00:00', 'end_date_time' => '2023-06-09 11:30:00', 'created_at' => now()],
                ['meal_type_id' => 3, 'meal_id' => 5, 'start_date_time' => '2023-06-09 13:00:00', 'end_date_time' => '2023-06-09 14:30:00', 'created_at' => now()],
                ['meal_type_id' => 4, 'meal_id' => 1, 'start_date_time' => '2023-06-09 19:00:00', 'end_date_time' => '2023-06-09 21:30:00', 'created_at' => now()],

            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_plans');
    }
};
