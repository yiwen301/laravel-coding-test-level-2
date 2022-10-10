<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('v1/users/login', 'App\Http\Controllers\API\User\LoginAccountController@execute')->name('login');

Route::group(['middleware' => ['api.user.secure']], function (): void {
    Route::group(['prefix' => '/v1/users', 'as' => 'users.'], function (): void {
        Route::delete('session', 'App\Http\Controllers\API\User\LogoutAccountController@execute')->name('logout');

        Route::get('', 'App\Http\Controllers\API\User\RetrieveUsersController@execute')->name('list');
        Route::get('{user_id}', 'App\Http\Controllers\API\User\RetrieveUserController@execute')->name('get');
        Route::post('', 'App\Http\Controllers\API\User\CreateUserController@execute')->name('create');
        Route::put('{user_id}', 'App\Http\Controllers\API\User\UpdateUserController@execute')->name('update');
        Route::patch('{user_id}', 'App\Http\Controllers\API\User\PatchUserController@execute')->name('patch');
        Route::delete('{user_id}', 'App\Http\Controllers\API\User\DeleteUserController@execute')->name('delete');
    });

    Route::group(['prefix' => '/v1/projects', 'as' => 'projects.'], function (): void {
        Route::get('', 'App\Http\Controllers\API\Project\RetrieveProjectsController@execute')->name('list');
        Route::get('{project_id}', 'App\Http\Controllers\API\Project\RetrieveProjectController@execute')->name('get');
        Route::post('', 'App\Http\Controllers\API\Project\CreateProjectController@execute')->name('create');
        Route::put('{project_id}', 'App\Http\Controllers\API\Project\UpdateProjectController@execute')->name('update');
        Route::patch('{project_id}', 'App\Http\Controllers\API\Project\PatchProjectController@execute')->name('patch');
        Route::delete('{project_id}', 'App\Http\Controllers\API\Project\DeleteProjectController@execute')
             ->name('delete');
    });

    Route::group(['prefix' => '/v1/tasks', 'as' => 'tasks.'], function (): void {
        Route::get('', 'App\Http\Controllers\API\Task\RetrieveTasksController@execute')->name('list');
        Route::get('{task_id}', 'App\Http\Controllers\API\Task\RetrieveTaskController@execute')->name('get');
        Route::post('', 'App\Http\Controllers\API\Task\CreateTaskController@execute')->name('create');
        Route::put('{task_id}', 'App\Http\Controllers\API\Task\UpdateTaskController@execute')->name('update');
        Route::patch('{task_id}', 'App\Http\Controllers\API\Task\PatchTaskController@execute')->name('patch');
        Route::delete('{task_id}', 'App\Http\Controllers\API\Task\DeleteTaskController@execute')->name('delete');
    });
});

