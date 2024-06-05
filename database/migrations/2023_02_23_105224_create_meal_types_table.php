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
        Schema::create('meal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('meal_types')->insert(
            [
                ['name' => 'Breakfast', 'created_at' => now()],
                ['name' => 'Brunch', 'created_at' => now()],
                ['name' => 'Lunch', 'created_at' => now()],
                ['name' => 'Dinner', 'created_at' => now()]

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
        Schema::dropIfExists('meal_types');
    }
};
