<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('f_a_q_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_type_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
        });

        DB::table('f_a_q_s')->insert(
            [
                [
                    'authorization_type_id' => null,
                    'question' => 'How can I change my password?',
                    'answer' => 'By navigating to the "Update profile" and scrolling down to the "update password" section, you can change your password. You can also contact an administrator to request a password change.'
                ],

                [
                    'authorization_type_id' => null,
                    'question' => 'How can I sign up for a meal?',
                    'answer' => 'By navigating to the "Schedule" page, you can view all the meals that are currently planned. By clicking on a meal, you can view the details of the meal and sign up for the meal by clicking the "Subscribe" button.'
                ],

                [
                    'authorization_type_id' => null,
                    'question' => 'How can I view the schedule?',
                    'answer' => 'By navigating to the "Schedule" page, you can view all planned meals, all tasks and all appointments which are relevant to you.'
                ],

                [
                    'authorization_type_id' => null,
                    'question' => 'How can I get more information about each page?',
                    'answer' => 'By navigating to the page you want to learn more about, you can click on the button "Start tour" to begin a tour which will walk you over the functionalities of each page.'
                ],


                [
                    'authorization_type_id' => 3,
                    'question' => 'How can I submit an invoice for work-related expenses?',
                    'answer' => 'By navigating to the "Submit Invoice" page, you can submit an invoice by providing the required information, uploading the invoice and pressing "submit".'
                ],

                [
                    'authorization_type_id' => 3,
                    'question' => 'How can I declare an absence?',
                    'answer' => 'By navigating to the "Declare Absence" page and providing the required information, you can declare an absence by pressing the "Save absence" button.'
                ],
                [
                    'authorization_type_id' => 3,
                    'question' => 'How can I remove myself from a task?',
                    'answer' => 'By navigating to the "Manage Tasks" page, you can view all tasks you are assigned to. You can remove yourself from the task by pressing on the "Remove yourself from this task" button next to the task. If you are the only person assigned to a task, this is not possible.'
                ],
                [
                    'authorization_type_id' => 3,
                    'question' => 'How can I create a task?',
                    'answer' => 'By navigating to the "Manage Tasks" page, you can click on the "New task" button. Then by filling in the required fields for the new task, you can create the task by pressing the "Save new task" button.'
                ],
                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I delete a user?',
                    'answer' => 'By navigating to the "Manage Users" page, you can click on the trash icon under a users information to delete them. Note that a user account must be set to "inactive" in order to delete them.'
                ],

                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I set a user as inactive/active?',
                    'answer' => 'By navigating to the "Manage Users" page, you can click on the pencil icon under a user in order to edit their information. At the bottom of the edit form, you can see a toggle to set a user as active or inactive. Note that inactive users have a pink background to show they are inactive.'
                ],

                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I edit a users information?',
                    'answer' => 'By navigating to the "Manage Users" page, you can click on the pencil icon under a user in order to edit their information.'
                ],

                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I see the files on the system?',
                    'answer' => 'By navigating to the "Manage Files" page, you can view a list of all the files on the system. You can download a file by clicking on the download button located next to it.'
                ],
                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I see the submitted invoices?',
                    'answer' => 'By navigating to the "Manage Invoices" page, you can view all submitted invoices. You can download an invoice by clicking on the download button located next to it.'
                ],
                [
                    'authorization_type_id' => 1,
                    'question' => 'How can I mark an invoice as paid?',
                    'answer' => 'By navigating to the "Manage Invoices" page, you can view all invoices. To mark an invoice as "paid", you can click on the pencil icon located next to it and use the "Payment status" toggle in the edit form to mark it as paid/unpaid.'
                ],
                [
                    'authorization_type_id' => 5,
                    'question' => 'What is a super admin?',
                    'answer' => 'A super admin is a user who has all the rights of an admin along with the rights to view confidential files in the system.'
                ],
                [
                    'authorization_type_id' => 5,
                    'question' => 'How can I make another user a super admin?',
                    'answer' => 'By navigating to the "Manage Users" page and clicking on the pencil icon underneath the user, you can change their "Authorization Type" to "Super Admin".'
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
        Schema::dropIfExists('f_a_q_s');
    }
};
