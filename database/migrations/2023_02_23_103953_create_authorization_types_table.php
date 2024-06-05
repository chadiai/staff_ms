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
        Schema::create('authorization_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('authorization_types')->insert(
            [
                ['name' => 'Admin', 'created_at' => now()],
                ['name' => 'Cook', 'created_at' => now()],
                ['name' => 'Staff', 'created_at' => now()],
                ['name' => 'Grandson', 'created_at' => now()],
                ['name' => 'Super Admin', 'created_at' => now()],
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
        Schema::dropIfExists('authorization_types');
    }
};
