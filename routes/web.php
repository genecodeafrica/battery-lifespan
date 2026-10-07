<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BatteryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\BatteryImportController;
use App\Http\Controllers\BatteryMeasurementController;
use App\Http\Controllers\BatteryReportPdfController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    Route::get(
        '/register',
        [AuthController::class, 'showRegister']
    )->name('register');

    Route::post(
        '/register',
        [AuthController::class, 'register']
    );
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
->middleware('auth')
->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Application Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Battery Management
    |--------------------------------------------------------------------------
    */

    /*
     * IMPORTANT:
     * Import routes must come before the resource route.
     */

    Route::get(
        '/batteries/import',
        [BatteryImportController::class, 'create']
    )->name('batteries.import.create');

    Route::post(
        '/batteries/import',
        [BatteryImportController::class, 'store']
    )->name('batteries.import.store');

    Route::resource(
        'batteries',
        BatteryController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Battery Measurements
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/batteries/{battery}/measurements',
        [MeasurementController::class, 'index']
    )->name('batteries.measurements.index');

    Route::get(
        '/batteries/{battery}/measurements/create',
        [BatteryMeasurementController::class, 'create']
    )->name('batteries.measurements.create');

    Route::post(
        '/batteries/{battery}/measurements',
        [BatteryMeasurementController::class, 'store']
    )->name('batteries.measurements.store');

    Route::get(
        '/batteries/{battery}/measurements/{measurement}',
        [MeasurementController::class, 'show']
    )->name('batteries.measurements.show');


    /*
    |--------------------------------------------------------------------------
    | AI Predictions
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/batteries/{battery}/predictions',
        [PredictionController::class, 'index']
    )->name('batteries.predictions.index');

    Route::post(
        '/batteries/{battery}/predictions',
        [PredictionController::class, 'predict']
    )->name('batteries.predictions.predict');


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports',
        [ReportController::class, 'index']
    )->name('reports.index');

    Route::get(
        '/reports/batteries/{battery}',
        [ReportController::class, 'battery']
    )->name('reports.battery');

    Route::get(
        '/reports/batteries/{battery}/pdf',
        [BatteryReportPdfController::class, 'generate']
    )->name('reports.battery.pdf');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'index']
    )->name('profile.index');

    Route::put(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        '/profile/password',
        [ProfileController::class, 'updatePassword']
    )->name('profile.password');


    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/settings',
        [SettingsController::class, 'index']
    )->name('settings.index');


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/{notification}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'markAllRead']
    )->name('notifications.read-all');


    /*
    |--------------------------------------------------------------------------
    | Administrator Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])
        ->prefix('admin')
        ->group(function () {

            Route::get(
                '/',
                [AdminController::class, 'dashboard']
            )->name('admin.dashboard');

            Route::get(
                '/users',
                [AdminController::class, 'users']
            )->name('admin.users');

            Route::get(
                '/users/create',
                [AdminController::class, 'createUser']
            )->name('admin.users.create');

            Route::post(
                '/users',
                [AdminController::class, 'storeUser']
            )->name('admin.users.store');

            Route::get(
                '/users/{user}/edit',
                [AdminController::class, 'editUser']
            )->name('admin.users.edit');

            Route::put(
                '/users/{user}',
                [AdminController::class, 'updateUser']
            )->name('admin.users.update');

            Route::patch(
                '/users/{user}/toggle-status',
                [AdminController::class, 'toggleStatus']
            )->name('admin.users.toggle-status');

            Route::delete(
                '/users/{user}',
                [AdminController::class, 'destroyUser']
            )->name('admin.users.destroy');
        });
});