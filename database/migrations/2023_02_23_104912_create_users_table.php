<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_type_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('staff_role_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('street_name_and_number')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('telephone');
            $table->string('allergy')->nullable();
            $table->boolean('active')->default(true);
            $table->string('dislike')->nullable();
            $table->string('task_color')->default('276FBF');
            $table->string('appointment_color')->default('A6BB8D');
            $table->string('meal_color')->default('F9A620');
            $table->rememberToken();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert(
            [
                ['authorization_type_id' => 4, 'staff_role_id' => null, 'country_id' => 1, 'name' => 'JohnDoe', 'email' => 'john.doe@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make("JohnDoe"),
                    'first_name' => 'John', 'last_name' => 'Doe', 'date_of_birth' => '2005-06-08', 'bank_account_number' => null,
                    'street_name_and_number' => null, 'city' => null, 'postal_code' => null, 'dislike' => 'Cheese', 'allergy' => null,
                    'telephone' => '02012341234', 'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620',
                    'created_at' => now()],
                ['authorization_type_id' => 3, 'staff_role_id' => 1, 'country_id' => 1, 'name' => 'DavidSmith', 'email' => 'david.smith@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('DavidSmith'),
                    'first_name' => 'David', 'last_name' => 'Smith', 'date_of_birth' => '1984-02-02', 'bank_account_number' => null, 'dislike' => null, 'allergy' => 'Shellfish',
                    'street_name_and_number' => 'Queen Street 68', 'city' => 'Nottingham', 'postal_code' => 'NG11 1DL', 'telephone' => '02076348244',
                    'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now(),],
                ['authorization_type_id' => 2, 'staff_role_id' => 4, 'country_id' => 1, 'name' => 'TylerBrown', 'email' => 'tyler.brown@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('TylerBrown'), 'dislike' => 'Tomato, eggs', 'allergy' => 'Peanuts',
                    'first_name' => 'Tyler', 'last_name' => 'Brown', 'date_of_birth' => '1976-03-07', 'bank_account_number' => 'GB29 NWBK 6016 1331 9268 19',
                    'street_name_and_number' => 'Mill Lane 8', 'city' => 'Blackburn', 'postal_code' => 'BB9 9PJ', 'telephone' => '02044348694',
                    'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now()],
                ['authorization_type_id' => 1, 'staff_role_id' => 3, 'country_id' => 2, 'name' => 'LaurienStroobants', 'email' => 'laurien.stroobants@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('LaurienStroobants'), 'dislike' => 'Ketchup', 'allergy' => null,
                    'first_name' => 'Laurien', 'last_name' => 'Stroobants', 'date_of_birth' => '1995-07-06', 'bank_account_number' => null,
                    'street_name_and_number' => 'Stationsstraat 62', 'city' => 'Westerlo', 'postal_code' => '2260', 'telephone' => '0478135560',
                    'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now()],
                ['authorization_type_id' => 5, 'staff_role_id' => null, 'country_id' => 1, 'name' => 'MaryAnderson', 'email' => 'mary.anderson@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('MaryAnderson'), 'dislike' => 'Carrot, corn, apple', 'allergy' => 'Milk, eggs',
                    'first_name' => 'Mary', 'last_name' => 'Anderson', 'date_of_birth' => '1958-09-09', 'bank_account_number' => null,
                    'street_name_and_number' => null, 'city' => null, 'postal_code' => null,
                    'telephone' => '02074837546', 'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now()],
                ['authorization_type_id' => 3, 'staff_role_id' => 2, 'country_id' => 1, 'name' => 'KateEvans', 'email' => 'kate.evans@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('KateEvans'), 'dislike' => 'Chicken', 'allergy' => null,
                    'first_name' => 'Kate', 'last_name' => 'Evans', 'date_of_birth' => '1985-05-09', 'bank_account_number' => null,
                    'street_name_and_number' => null, 'city' => null, 'postal_code' => null,
                    'telephone' => '02099328544', 'profile_photo_path' => null, 'active' => true,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now()],
                ['authorization_type_id' => 3, 'staff_role_id' => 5, 'country_id' => 1, 'name' => 'MichaelJones', 'email' => 'michael.jones@gmail.com',
                    'email_verified_at' => now(), 'password' => Hash::make('MichaelJones'), 'dislike' => null, 'allergy' => null,
                    'first_name' => 'Michael', 'last_name' => 'Jones', 'date_of_birth' => '1977-01-04', 'bank_account_number' => null,
                    'street_name_and_number' => null, 'city' => null, 'postal_code' => null,
                    'telephone' => '02079224534', 'profile_photo_path' => null, 'active' => false,'task_color' => '276FBF','appointment_color' => 'A6BB8D','meal_color' => 'F9A620', 'created_at' => now()],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

