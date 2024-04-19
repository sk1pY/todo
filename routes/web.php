<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;

//Route::resource('bbs', \App\Http\Controllers\TasksController::class);
Route::controller(TasksController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/test', 'test')->name('test');
    });
    Route::get('/test', 'test')->name('test');
    Route::get('/', 'index')->name('index');
    Route::get('/task/{id}', 'task')->name('about_task');
    Route::post('/store','create')->name('create');
//Route::delete('/delete', [\App\Http\Controllers\TasksController::class,'delete'])->name('delete');
    Route::delete('/delete/{id}', 'delete')->name('delete')->where('id', '[0-9]+');
    Route::delete('/destroy_all', 'destroy_all')->name('destroy_all');
    Route::put('/update/{id}', 'update')->name('update');

});

//Route::fallback([\App\Http\Controllers\TasksController::class, 'error']);//Должен быть в самом конце списка
