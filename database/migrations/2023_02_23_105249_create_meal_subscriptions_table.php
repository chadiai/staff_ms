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
        Schema::create('meal_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('meal_plan_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });

        DB::table('meal_subscriptions')->insert(
            [
                ['user_id' => 2, 'meal_plan_id' => 4, 'created_at' => now()],
                ['user_id' => 1, 'meal_plan_id' => 3, 'created_at' => now()],
                ['user_id' => 3, 'meal_plan_id' => 4, 'created_at' => now()],
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
        Schema::dropIfExists('meal_subscriptions');
    }
};
