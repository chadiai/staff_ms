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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color_hex_code');
            $table->foreignId('super_category_id')->nullable()->constrained('categories')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });

        DB::table('categories')->insert(
            [
                [
                    'name' => 'Gardening',
                    'description' => null,
                    'color_hex_code' => '609966',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Cleaning',
                    'description' => null,
                    'color_hex_code' => 'B4E4FF',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Estates',
                    'description' => null,
                    'color_hex_code' => 'D61355',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Shopping',
                    'description' => null,
                    'color_hex_code' => '609966',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Estate A',
                    'description' => 'The first estate',
                    'color_hex_code' => 'F94A29',
                    'super_category_id' => 3,
                ],
                [
                    'name' => 'Estate B',
                    'description' => 'The second estate',
                    'color_hex_code' => 'FCE22A',
                    'super_category_id' => 3,
                ],
                [
                    'name' => 'Grocery',
                    'description' => 'Food supply',
                    'color_hex_code' => 'FCE22A',
                    'super_category_id' => 4,
                ],
                [
                    'name' => 'Clothing',
                    'description' => null,
                    'color_hex_code' => 'E96479',
                    'super_category_id' => 4,
                ],
                [
                    'name' => 'Legal',
                    'description' => 'Legal documents',
                    'color_hex_code' => 'FF0000',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Health Care',
                    'description' => null,
                    'color_hex_code' => 'FA8072',
                    'super_category_id' => null,
                ],
                [
                    'name' => 'Dentist',
                    'description' => null,
                    'color_hex_code' => '63C5DA',
                    'super_category_id' => 10,
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
        Schema::dropIfExists('categories');
    }
};
