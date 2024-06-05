<?php

use App\Http\Livewire\Admin\Categories;
use App\Http\Livewire\Admin\Invoices;
use App\Http\Livewire\Admin\Users;
use App\Http\Livewire\Cook\ScheduleMeals;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Staff\Absence;
use App\Http\Livewire\Staff\SubmitInvoice;
use App\Http\Livewire\User\FAQs;
use App\Http\Livewire\User\Schedule;
use App\Http\Livewire\Admin\Files;
use App\Models\Event;
use App\Models\MealPlan;
use App\Models\ScheduledAbsence;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Cook\Meals;
use App\Http\Livewire\Staff\Tasks;
use App\Http\Livewire\Staff\AbsenceSchedule;
use App\Http\Livewire\Admin\Appointments;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::view('/', 'home')->name('home');
Route::get('/', Dashboard::class)->name('home');

//User Routes here
Route::middleware(['auth', 'active'])->prefix('user')->name('user.')->group(function () {
    Route::get('f-a-qs', FAQs::class)->name('f-a-qs');
    Route::get('schedule', Schedule::class)->name('schedule');
    Route::get('/events', function () {
        $events = array();
        $getEvents = Event::all();
        $getMealPlans = MealPlan::all();
        foreach ($getEvents as $event) {
            $isTask = $event->is_task;
            $events[] = [
                'groupId' => 1,
                'color' => $isTask ? '#'.auth()->user()->task_color : '#'.auth()->user()->appointment_color,
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->start_date_time,
                'end' => $event->end_date_time,
            ];
        }
        foreach ($getMealPlans as $event) {
            $events[] = [
                'groupId' => 2,
                'color' => '#'.auth()->user()->meal_color,
                'id' => $event->id,
                'title' => $event->meal->name,
                'start' => $event->start_date_time,
                'end' => $event->end_date_time,
            ];
        }
        return response()->json($events);
    });
});

//Cook Routes here
Route::middleware(['auth', 'active', 'cook.authorization'])->prefix('cook')->name('cook.')->group(function () {
    Route::get('meals', Meals::class)->name('meals');
    Route::get('schedule-meals', ScheduleMeals::class)->name('schedule-meals');
});

//Staff Routes here
Route::middleware(['auth', 'active', 'staff.authorization'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('tasks', Tasks::class)->name('tasks');
    Route::get('absence', Absence::class)->name('absence');
    Route::get('submit-invoice', SubmitInvoice::class)->name('submit-invoice');
    Route::get('absence-schedule', AbsenceSchedule::class)->name('absence-schedule');
    Route::get('events', function () {
        $events = array();
        $getAbsences = ScheduledAbsence::all();
        foreach ($getAbsences as $event) {
            $events[] = [
                'groupId' => 1,
                'color' => $event->user_id == 4 ? '#CAE9f5' : '#ff9a98',
                'textColor' => $event->user_id == 4 ? 'black' : 'white',
                'id' => $event->id,
                'title' => $event->user->first_name . ' ' . $event->user->last_name,
                'start' => $event->start_date_time,
                'end' => $event->end_date_time,
            ];
        }
        return response()->json($events);
    });
});


//Admin Routes
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('invoices', Invoices::class)->name('invoices');
    Route::get('files', Files::class)->name('files');
    Route::get('users', Users::class)->name('users');
    Route::get('appointments', Appointments::class)->name('appointments');
    Route::get('categories', Categories::class)->name('categories');


});

//Route::controller(Invoices::class)->group(function () {
//    Route::get('invoice/download/{id}', 'downloadInvoice')->middleware(['auth', 'active', 'admin']);
//});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'active',
])->group(function () {
    /*Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');*/
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});
