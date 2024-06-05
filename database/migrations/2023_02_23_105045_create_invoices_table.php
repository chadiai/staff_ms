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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('submitting_user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->string('title');
            $table->string('number')->nullable()->unique();
            $table->dateTime('submission_date');
            $table->date('due_date');
            $table->float('amount');
            $table->string('url');
            $table->boolean('is_paid')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('invoices')->insert(
            [
                [
                    'category_id' => 1,
                    'submitting_user_id' => 2,
                    'title' => 'Gardening Invoices',
                    'number' => 'INV-2023-001',
                    'submission_date' => '2023-02-04',
                    'due_date' => '2023-03-04',
                    'amount' => 100.60,
                    'url' => 'Documents/Invoices/gardening_invoice.pdf',
                    'is_paid' => true
                ],
                [
                    'category_id' => 2,
                    'submitting_user_id' => 6,
                    'title' => 'Cleaning Services Invoices',
                    'number' => 'INV-2023-002',
                    'submission_date' => '2023-02-10',
                    'due_date' => '2023-03-10',
                    'amount' => 80.00,
                    'url' => 'Documents/Invoices/cleaning_services_invoice.pdf',
                    'is_paid' => false
                ],
                [
                    'category_id' => 4,
                    'submitting_user_id' => 4,
                    'title' => 'Shopping Receipt',
                    'number' => 'INV-2023-003',
                    'submission_date' => '2023-01-26',
                    'due_date' => '2023-02-26',
                    'amount' => 130.00,
                    'url' => 'Documents/Invoices/shopping_receipt.pdf',
                    'is_paid' => true
                ],
                [
                    'category_id' => 7,
                    'submitting_user_id' => 4,
                    'title' => 'Grocery Receipt',
                    'number' => null,
                    'submission_date' => '2023-01-27',
                    'due_date' => '2023-02-27',
                    'amount' => 50.60,
                    'url' => 'Documents/Invoices/grocery_receipt.pdf',
                    'is_paid' => false
                ],
                [
                    'category_id' => 8,
                    'submitting_user_id' => 4,
                    'title' => 'Clothing Invoices',
                    'number' => null,
                    'submission_date' => '2023-02-01',
                    'due_date' => '2023-03-01',
                    'amount' => 90.40,
                    'url' => 'Documents/Invoices/clothing_invoice.pdf',
                    'is_paid' => true
                ],
                [
                    'category_id' => 9,
                    'submitting_user_id' => 4,
                    'title' => 'January Taxes',
                    'number' => 'INV-2023-004',
                    'submission_date' => '2023-01-07',
                    'due_date' => '2023-02-07',
                    'amount' => 150.00,
                    'url' => 'Documents/Invoices/january_taxes.pdf',
                    'is_paid' => true
                ],
                [
                    'category_id' => 10,
                    'submitting_user_id' => 4,
                    'title' => 'Blood Test Bill',
                    'number' => 'INV-2023-005',
                    'submission_date' => '2023-03-15',
                    'due_date' => '2023-03-15',
                    'amount' => 40.75,
                    'url' => 'Documents/Invoices/blood_test_bill.pdf',
                    'is_paid' => false
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
        Schema::dropIfExists('invoices');
    }
};
