<?php


use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SocialContentController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
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

define('PAGINATION_COUNT', 11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {





    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');


        /*         start  update login admin                 */
        Route::get('/admin/edit/{id}', [LoginController::class, 'editlogin'])->name('admin.login.edit');
        Route::post('/admin/update/{id}', [LoginController::class, 'updatelogin'])->name('admin.login.update');
        /*         end  update login admin                */

        /// Role and permission
        Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController', ['as' => 'admin']);
        Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
        Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
        Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
        Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
        Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
        Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

        Route::get('/permissions/{guard_name}', function ($guard_name) {
            return response()->json(Permission::where('guard_name', $guard_name)->get());
        });


        // Route for ajax


        // Resource Route
        Route::resource('users', UserController::class);

        // ── Academy Management ──────────────────────────────
        Route::resource('players', PlayerController::class)
             ->except(['show']);

        Route::resource('subscriptions', SubscriptionController::class)
             ->except(['show']);

        Route::post('subscriptions/{subscription}/freeze',   [SubscriptionController::class, 'freeze'])  ->name('subscriptions.freeze');
        Route::post('subscriptions/{subscription}/unfreeze', [SubscriptionController::class, 'unfreeze'])->name('subscriptions.unfreeze');

        // Social
        Route::get  ('social',                         [SocialContentController::class, 'scheduleForm'])    ->name('social.index');
        Route::get  ('social/schedule',                [SocialContentController::class, 'scheduleForm'])    ->name('social.schedule');
        Route::post ('social/schedule',                [SocialContentController::class, 'scheduleGenerate'])->name('social.schedule.generate');
        Route::post ('social/mark-all-today',          [SocialContentController::class, 'markAllToday'])   ->name('social.markAllToday');
        Route::post ('social',                         [SocialContentController::class, 'store'])           ->name('social.store');
        Route::patch('social/{social}',                [SocialContentController::class, 'update'])          ->name('social.update');
        Route::delete('social/{social}',               [SocialContentController::class, 'destroy'])         ->name('social.destroy');
        Route::patch('social/{social}/mark-published', [SocialContentController::class, 'markPublished'])   ->name('social.markPublished');
    });
});



Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
