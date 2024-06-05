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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('uploading_user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('upload_date');
            $table->string('name');
            $table->string('url');
            $table->boolean('is_confidential')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('files')->insert(
            [
                [
                    'category_id' => 1,
                    'uploading_user_id' => 4,
                    'upload_date' => '2023-04-11',
                    'name' => 'Pest Control Plan',
                    'url' => 'Documents/Files/pest_control_plan.pdf',
                    'is_confidential' => false
                ],
                [
                    'category_id' => 2,
                    'uploading_user_id' => 4,
                    'upload_date' => '2023-04-11',
                    'name' => 'Cleaning Service Contract',
                    'url' => 'Documents/Files/cleaning_service_contract.docx',
                    'is_confidential' => false
                ],
                [
                    'category_id' => 5,
                    'uploading_user_id' => 5,
                    'upload_date' => '2023-04-11',
                    'name' => 'Estate A Security Plane',
                    'url' => 'Documents/Files/estate_a_security_plan.pdf',
                    'is_confidential' => true
                ],
                [
                    'category_id' => 6,
                    'uploading_user_id' => 5,
                    'upload_date' => '2023-04-11',
                    'name' => 'Estate B Security Plane',
                    'url' => 'Documents/Files/estate_b_security_plan.pdf',
                    'is_confidential' => true
                ],
                [
                    'category_id' => 4,
                    'uploading_user_id' => 4,
                    'upload_date' => '2023-04-11',
                    'name' => 'Shopping Budget',
                    'url' => 'Documents/Files/shopping_budget.xlsx',
                    'is_confidential' => false
                ],
                [
                    'category_id' => 7,
                    'uploading_user_id' => 4,
                    'upload_date' => '2023-04-11',
                    'name' => 'Grocery List Template',
                    'url' => 'Documents/Files/grocery_list_template.xlsx',
                    'is_confidential' => false
                ],
                [
                    'category_id' => 8,
                    'uploading_user_id' => 5,
                    'upload_date' => '2023-04-11',
                    'name' => 'Clothing Donation Log',
                    'url' => 'Documents/Files/clothing_donation_log.xlsx',
                    'is_confidential' => true
                ]
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
        Schema::dropIfExists('files');
    }
};
