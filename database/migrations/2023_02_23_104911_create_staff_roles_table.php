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
        Schema::create('staff_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('staff_roles')->insert(
            [
                ['name' => 'Gardener', 'created_at' => now()],
                ['name' => 'Cleaner', 'created_at' => now()],
                ['name' => 'Au pair', 'created_at' => now()],
                ['name' => 'Cook', 'created_at' => now()],
                ['name' => 'Handyman', 'created_at' => now()],
                ['name' => 'Interior decorator', 'created_at' => now()],
                ['name' => 'Physiotherapist', 'created_at' => now()],
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
        Schema::dropIfExists('staff_roles');
    }
};
