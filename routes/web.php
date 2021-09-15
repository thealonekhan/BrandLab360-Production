<?php
use App\Http\Controllers\ApplicationController;
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

Route::group(['middleware' => ['get.menu']], function () {
    Route::any('/', [ApplicationController::class, 'index'])->name('dashboard.overview.ajax');
    Route::any('/audience/overview', 'AudienceController@overview')->name('audience.overview.ajax');
    Route::any('/audience/devices', 'AudienceController@devices')->name('audience.devices.ajax');
    Route::any('/behavior/event/overview', 'BehaviorController@overview')->name('behavior.overview.ajax');
    Route::resource('settings', 'SettingController');
    // Route::any('/behavior/event/topevents', 'BehaviorController@topevents')->name('behavior.topevents.ajax');
    Route::group(['middleware' => ['role:user']], function () {
        
    });
    Auth::routes();

    Route::resource('resource/{table}/resource', 'ResourceController')->names([
        'index'     => 'resource.index',
        'create'    => 'resource.create',
        'store'     => 'resource.store',
        'show'      => 'resource.show',
        'edit'      => 'resource.edit',
        'update'    => 'resource.update',
        'destroy'   => 'resource.destroy'
    ]);

    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('roles', 'RolesController');
        Route::get('/roles/move/move-up', 'RolesController@moveUp')->name('roles.up');
        Route::get('/roles/move/move-down', 'RolesController@moveDown')->name('roles.down');
        Route::prefix('menu/element')->group(function () { 
            Route::get('/', 'MenuElementController@index')->name('menu.index');
            Route::get('/move-up', 'MenuElementController@moveUp')->name('menu.up');
            Route::get('/move-down', 'MenuElementController@moveDown')->name('menu.down');
            Route::get('/create', 'MenuElementController@create')->name('menu.create');
            Route::post('/store', 'MenuElementController@store')->name('menu.store');
            Route::get('/get-parents', 'MenuElementController@getParents');
            Route::get('/edit', 'MenuElementController@edit')->name('menu.edit');
            Route::post('/update', 'MenuElementController@update')->name('menu.update');
            Route::get('/show', 'MenuElementController@show')->name('menu.show');
            Route::get('/delete', 'MenuElementController@delete')->name('menu.delete');
        });
        Route::prefix('menu/menu')->group(function () { 
            Route::get('/', 'MenuController@index')->name('menu.menu.index');
            Route::get('/create', 'MenuController@create')->name('menu.menu.create');
            Route::post('/store', 'MenuController@store')->name('menu.menu.store');
            Route::get('/edit', 'MenuController@edit')->name('menu.menu.edit');
            Route::post('/update', 'MenuController@update')->name('menu.menu.update');
            Route::get('/delete', 'MenuController@delete')->name('menu.menu.delete');
        });
    });
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::resource('users', 'UsersController');
        Route::resource('projects', 'ProjectController');
    });
});