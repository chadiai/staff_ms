<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->string('reason_of_absence');
            $table->timestamps();
        });

        DB::table('scheduled_absences')->insert(
            [
                [
                    'user_id' => 2,
                    'created_at' => now(),
                    'start_date_time' => "2022-12-02 08:00:00",
                    'end_date_time' => "2022-12-05 12:00:00",
                    'reason_of_absence' => 'Vacation'
                ],
                [
                    'user_id' => 6,
                    'created_at' => now(),
                    'start_date_time' => "2023-01-13 09:00:00",
                    'end_date_time' => "2023-01-13 16:00:00",
                    'reason_of_absence' => 'Funeral'
                ],
                [
                    'user_id' => 2,
                    'created_at' => now(),
                    'start_date_time' => "2023-01-21 13:00:00",
                    'end_date_time' => "2023-01-21 14:30:00",
                    'reason_of_absence' => 'Doctor\'s appointment'
                ],
                [
                    'user_id' => 6,
                    'created_at' => now(),
                    'start_date_time' => "2023-02-02 08:00:00",
                    'end_date_time' => "2023-02-02 12:00:00",
                    'reason_of_absence' => 'Family emergency'
                ],
                //
                [
                    'user_id' => 2,
                    'created_at' => now(),
                    'start_date_time' => "2023-05-20 08:00:00",
                    'end_date_time' => "2023-05-25 12:00:00",
                    'reason_of_absence' => 'Vacation'
                ],
                [
                    'user_id' => 6,
                    'created_at' => now(),
                    'start_date_time' => "2023-05-28 09:00:00",
                    'end_date_time' => "2023-05-28 16:00:00",
                    'reason_of_absence' => 'Funeral'
                ],
                [
                    'user_id' => 4,
                    'created_at' => now(),
                    'start_date_time' => "2023-06-11 13:00:00",
                    'end_date_time' => "2023-06-11 14:30:00",
                    'reason_of_absence' => 'Doctor\'s appointment'
                ],
                [
                    'user_id' => 3,
                    'created_at' => now(),
                    'start_date_time' => "2023-06-14 08:00:00",
                    'end_date_time' => "2023-06-14 12:00:00",
                    'reason_of_absence' => 'Family emergency'
                ],
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
        Schema::dropIfExists('scheduled_absences');
    }
};
