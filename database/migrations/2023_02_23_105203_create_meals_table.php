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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('meals')->insert(
            [
                ['name' => 'Lasagne', 'created_at' => now()],
                ['name' => 'Bacon and eggs', 'created_at' => now()],
                ['name' => 'Baked beans', 'created_at' => now()],
                ['name' => 'Sausages with bacon', 'created_at' => now()],
                ['name' => 'Fish and chips', 'created_at' => now()]
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
        Schema::dropIfExists('meals');
    }
};
