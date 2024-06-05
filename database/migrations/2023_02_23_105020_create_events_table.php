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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_creation_request_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->string('name');
            $table->string('description');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->string('location')->nullable();
            $table->boolean('is_task')->default(false);
            $table->timestamps();
        });
        DB::table('events')->insert(
            [
                [
                    'event_creation_request_id' => 1,
                    'category_id' => 2,
                    'name' => 'Cleaning: bedroom',
                    'description' => 'Change bed sheets and clean floor',
                    'start_date_time' => '2023-03-04 13:00:00',
                    'end_date_time' => '2023-03-04 14:00:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 2,
                    'category_id' => 1,
                    'name' => 'Gardening: backyard',
                    'description' => 'Mow the lawn and trim bushes. Planting, watering, and fertilizing plants and flowers.',
                    'start_date_time' => '2023-03-11 08:00:00',
                    'end_date_time' => '2023-03-11 09:30:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 2,
                    'category_id' => 1,
                    'name' => 'Gardening: backyard',
                    'description' => 'Mow the lawn and trim bushes. Planting, watering, and fertilizing plants and flowers.',
                    'start_date_time' => '2023-03-18 08:00:00',
                    'end_date_time' => '2023-03-18 09:30:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 3,
                    'category_id' => 2,
                    'name' => 'Cleaning windows',
                    'description' => 'Cleaning windows, mirrors, and glass surfaces',
                    'start_date_time' => '2023-03-05 08:00:00',
                    'end_date_time' => '2023-03-05 09:30:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 3,
                    'category_id' => 2,
                    'name' => 'Cleaning windows',
                    'description' => 'Cleaning windows, mirrors, and glass surfaces',
                    'start_date_time' => '2023-04-02 08:00:00',
                    'end_date_time' => '2023-04-02 09:30:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 3,
                    'category_id' => 2,
                    'name' => 'Cleaning windows',
                    'description' => 'Cleaning windows, mirrors, and glass surfaces',
                    'start_date_time' => '2023-04-30 08:00:00',
                    'end_date_time' => '2023-04-30 09:30:00',
                    'location' => null,
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 4,
                    'category_id' => 7,
                    'name' => 'Going to the grocery store',
                    'description' => 'Buying fruits, vegetables, meat, dairy, bread, cereal, canned goods, cleaning supplies.',
                    'start_date_time' => '2023-03-11 10:00:00',
                    'end_date_time' => '2023-03-11 13:30:00',
                    'location' => 'Lidl',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 4,
                    'category_id' => 7,
                    'name' => 'Going to the grocery store',
                    'description' => 'Buying fruits, vegetables, meat, dairy, bread, cereal, canned goods, cleaning supplies.',
                    'start_date_time' => '2023-03-18 10:00:00',
                    'end_date_time' => '2023-03-18 13:30:00',
                    'location' => 'Lidl',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 4,
                    'category_id' => 7,
                    'name' => 'Going to the grocery store',
                    'description' => 'Buying fruits, vegetables, meat, dairy, bread, cereal, canned goods, cleaning supplies.',
                    'start_date_time' => '2023-03-25 10:00:00',
                    'end_date_time' => '2023-03-25 13:30:00',
                    'location' => 'Lidl',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 4,
                    'category_id' => 7,
                    'name' => 'Going to the grocery store',
                    'description' => 'Buying fruits, vegetables, meat, dairy, bread, cereal, canned goods, cleaning supplies.',
                    'start_date_time' => '2023-04-01 10:00:00',
                    'end_date_time' => '2023-04-01 13:30:00',
                    'location' => 'Lidl',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 4,
                    'category_id' => 7,
                    'name' => 'Going to the grocery store',
                    'description' => 'Buying fruits, vegetables, meat, dairy, bread, cereal, canned goods, cleaning supplies.',
                    'start_date_time' => '2023-04-08 10:00:00',
                    'end_date_time' => '2023-04-08 13:30:00',
                    'location' => 'Lidl',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    //12
                    'event_creation_request_id' => 5,
                    'category_id' => 11,
                    'name' => 'Dentist appointment',
                    'description' => 'Bring grandson to the dentist.',
                    'start_date_time' => '2023-04-15 10:00:00',
                    'end_date_time' => '2023-04-15 13:30:00',
                    'location' => '23rd ave',
                    'is_task' => false,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 6,
                    'category_id' => 11,
                    'name' => 'Dentist appointment',
                    'description' => 'Bring grandson to the dentist.',
                    'start_date_time' => '2023-04-22 10:00:00',
                    'end_date_time' => '2023-04-22 13:30:00',
                    'location' => '23rd ave',
                    'is_task' => false,
                    'created_at' => now(),
                ],
                [
                    //14
                    'event_creation_request_id' => 5,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-04-22 16:00:00',
                    'end_date_time' => '2023-04-22 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    //15
                    'event_creation_request_id' => 7,
                    'category_id' => 10,
                    'name' => 'Appointment hairdresser',
                    'description' => 'Go with John to the hairdresser',
                    'start_date_time' => '2023-05-27 15:00:00',
                    'end_date_time' => '2023-05-27 16:00:00',
                    'location' => 'hairdresser Luke',
                    'is_task' => false,
                    'created_at' => now(),
                ],
                [
                    //16
                    'event_creation_request_id' => 8,
                    'category_id' => 11,
                    'name' => 'Dentist appointment',
                    'description' => 'Bring grandson to the dentist.',
                    'start_date_time' => '2023-05-24 11:00:00',
                    'end_date_time' => '2023-05-24 12:00:00',
                    'location' => '23rd ave',
                    'is_task' => false,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 9,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-05-15 16:00:00',
                    'end_date_time' => '2023-05-15 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 9,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-05-22 16:00:00',
                    'end_date_time' => '2023-05-22 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 9,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-05-29 16:00:00',
                    'end_date_time' => '2023-05-29 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 9,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-06-05 16:00:00',
                    'end_date_time' => '2023-06-05 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
                ],
                [
                    'event_creation_request_id' => 9,
                    'category_id' => 1,
                    'name' => 'Trimming the bushes',
                    'description' => 'Helping gardener with trimming the bushes',
                    'start_date_time' => '2023-06-12 16:00:00',
                    'end_date_time' => '2023-06-12 17:00:00',
                    'location' => 'Estate A',
                    'is_task' => true,
                    'created_at' => now(),
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
        Schema::dropIfExists('events');
    }
};
