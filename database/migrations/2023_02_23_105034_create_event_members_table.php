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
        Schema::create('event_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('event_members')->insert(
            [
                [
                    'user_id' => 6,
                    'event_id' => 1,
                ],
                [
                    'user_id' => 3,
                    'event_id' => 1,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 2,
                ],
                [
                    'user_id' => 5,
                    'event_id' => 2,
                ],
                [
                    'user_id' => 7,
                    'event_id' => 2,
                ],

                [
                    'user_id' => 2,
                    'event_id' => 3,
                ],
                [
                    'user_id' => 6,
                    'event_id' => 4,
                ],
                [
                    'user_id' => 6,
                    'event_id' => 5,
                ],
                [
                    'user_id' => 6,
                    'event_id' => 6,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 7,
                ],
                [
                    'user_id' => 1,
                    'event_id' => 7,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 8,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 9,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 10,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 11,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 12,
                ],
                [
                    'user_id' => 1,
                    'event_id' => 12,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 13,
                ],
                [
                    'user_id' => 1,
                    'event_id' => 13,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 14,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 14,
                ],
                [
                    'user_id' => 1,
                    'event_id' => 15,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 15,
                ],
                [
                    'user_id' => 1,
                    'event_id' => 16,
                ],
                [
                    'user_id' => 4,
                    'event_id' => 16,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 17,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 18,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 19,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 20,
                ],
                [
                    'user_id' => 2,
                    'event_id' => 21,
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
        Schema::dropIfExists('event_members');
    }
};
