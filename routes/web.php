<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // 👤 Rotas do Profile (Breeze)
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::post('/avatar', 'uploadAvatar')->name('avatar');
        Route::post('/theme', 'updateTheme')->name('theme');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // 🚀 Rotas do SkyFlow (Tarefas) - Padrão REST puro!
    Route::controller(TodoController::class)->prefix('todos')->name('todos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{todo}', 'show')->name('show');
        Route::get('/{todo}/edit', 'edit')->name('edit');
        Route::put('/{todo}', 'update')->name('update');
        Route::patch('/{todo}/toggle', 'toggle')->name('toggle');
        Route::delete('/{todo}', 'destroy')->name('destroy');

        // 🗂️ Checklist
        Route::post('/{todo}/checklist', 'checklistStore')->name('checklist.store');
        Route::patch('/{todo}/checklist/{index}/toggle', 'checklistToggle')->name('checklist.toggle');
        Route::delete('/{todo}/checklist/{index}', 'checklistDestroy')->name('checklist.destroy');

        // 💬 Comentários
        Route::post('/{todo}/comments', 'commentStore')->name('comments.store');

        // 📎 Anexos
        Route::post('/{todo}/attachments', 'attachmentStore')->name('attachments.store');
        Route::delete('/{todo}/attachments/{index}', 'attachmentDestroy')->name('attachments.destroy');

        // 🏷️ Etiquetas
        Route::post('/{todo}/labels', 'labelStore')->name('labels.store');
        Route::delete('/{todo}/labels/{index}', 'labelDestroy')->name('labels.destroy');
    });

    // 📁 Rotas do SkyFlow (Categorias)
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::put('/{category}', 'update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });

    // 📝 Rotas do SkyFlow (Bloquinho de Notas)
    Route::controller(NoteController::class)->prefix('notes')->name('notes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/{note}', 'update')->name('update');
        Route::delete('/{note}', 'destroy')->name('destroy');
    });

    // 📅 Rotas do SkyFlow (Calendário)
    Route::controller(CalendarController::class)->prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/events', 'events')->name('events');
        Route::patch('/reschedule', 'reschedule')->name('reschedule');
    });

    // 📊 Rotas do SkyFlow (Kanban)
   Route::controller(KanbanController::class)->prefix('kanban')->name('kanban.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/columns', 'columns')->name('columns');
    Route::post('/move', 'move')->name('move');
    Route::post('/column/create', 'createColumn')->name('column.create');
    Route::delete('/column/{columnKey}', 'deleteColumn')->name('column.delete');
});

    // Toggle view mode (list vs kanban)
    Route::post('/todos/view-toggle', [TodoController::class, 'toggleViewMode'])->name('todos.view-toggle');

    // 🔍 Busca Global (SKY-FLOW-XIV)
    Route::get('/search', [SearchController::class, 'global'])->name('search.global');
});

require __DIR__ . '/auth.php';
