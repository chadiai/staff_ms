<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('countries')->insert(
            [
                ['name' => 'United Kingdom', 'created_at' => now()],
                ['name' => 'Belgium', 'created_at' => now()],
                ['name' => 'Ireland', 'created_at' => now()],
                ['name' => 'The Netherlands', 'created_at' => now()],
                ['name' => 'France', 'created_at' => now()],
                ['name' => 'Denmark', 'created_at' => now()],
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
        Schema::dropIfExists('countries');
    }
};
