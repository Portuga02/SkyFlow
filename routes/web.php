<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // 👤 Rotas do Profile (Breeze) - Voltou ao normal (sem o {todo})
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::controller(TodoController::class)->prefix('todos')->name('todos.')->group(function () {
        Route::get('/', 'index')->name('index');             // todos.index
        Route::get('/create', 'create')->name('create');     // todos.create
        Route::post('/', 'store')->name('store');            // todos.store
        Route::get('/{todo}', 'show')->name('show');         // todos.show

        // As duas rotas abaixo agora apontam para os métodos corretos do novo Controller!
        Route::get('/{todo}/edit', 'edit')->name('edit');    // todos.edit
        Route::put('/{todo}', 'update')->name('update');     // todos.update
        Route::patch('/{todo}/toggle', 'toggle')->name('toggle'); // todos.toggle
        Route::delete('/{todo}', 'destroy')->name('destroy');// todos.destroy
    });


    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');                 // categories.index
        Route::get('/create', 'create')->name('create');         // categories.create
        Route::post('/', 'store')->name('store');                // categories.store
        Route::get('/{category}/edit', 'edit')->name('edit');    // categories.edit
        Route::put('/{category}', 'update')->name('update');     // categories.update
        Route::delete('/{category}', 'destroy')->name('destroy');// categories.destroy
    });
});

require __DIR__ . '/auth.php';