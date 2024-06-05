<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('event_creation_requests', function (Blueprint $table) {
            $table->id();

            $table->integer('number_of_occurrences');
            $table->foreignId('recurrence_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('event_creation_requests')->insert(
            [
                // tasks for staff member
                [
                    'number_of_occurrences' => 1,
                    'recurrence_id' => null,
                    'user_id' => 6,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 2,
                    'recurrence_id' => 2,
                    'user_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 3,
                    'recurrence_id' => 3,
                    'user_id' => 6,
                    'created_at' => now(),
                ],
                // tasks for au pair
                [
                    'number_of_occurrences' => 4,
                    'recurrence_id' => 2,
                    'user_id' => 4,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 3,
                    'recurrence_id' => 2,
                    'user_id' => 4,
                    'created_at' => now(),
                ],
                // appointment for au pair
                [
                    'number_of_occurrences' => 2,
                    'recurrence_id' => 2,
                    'user_id' => 4,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 1,
                    'recurrence_id' => null,
                    'user_id' => 4,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 1,
                    'recurrence_id' => null,
                    'user_id' => 4,
                    'created_at' => now(),
                ],
                [
                    'number_of_occurrences' => 5,
                    'recurrence_id' => 2,
                    'user_id' => 4,
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
        Schema::dropIfExists('event_creation_requests');
    }
};
