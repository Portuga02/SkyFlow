<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 🚪 Login
Route::get('/', function () {
    return view('auth.login');
});

// 📊 Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    $stats = array(
        'total'     => $user->todos()->count(),
        'pending'   => $user->todos()->where(function ($query) {
            $query->where('is_completed', 0)->orWhereNull('is_completed');
        })->count(),
        'completed' => $user->todos()->where('is_completed', 1)->count(),
    );

    $urgentTodos = $user->todos()
        ->where(function ($query) {
            $query->where('is_completed', 0)->orWhereNull('is_completed');
        })
        ->whereIn('priority', array('high', 'highest'))
        ->orderByRaw('due_date IS NULL, due_date ASC')
        ->take(5)
        ->get();

    $recentNotes = \App\Models\Note::where('user_id', $user->id)
        ->latest()
        ->take(3)
        ->get();

    $categories = \App\Models\Category::where('user_id', $user->id)
        ->withCount(array('todos' => function ($query) {
            $query->where(function ($q) {
                $q->where('is_completed', 0)->orWhereNull('is_completed');
            });
        }))
        ->orderByDesc('todos_count')
        ->take(4)
        ->get();

    return view('dashboard', compact('stats', 'urgentTodos', 'recentNotes', 'categories'));
})->middleware(array('auth', 'verified'))->name('dashboard');

//  Rotas Autenticadas
Route::middleware('auth')->group(function () {
    
    // 👥 Equipe
    Route::get('/equipe', [TeamController::class, 'index'])->name('team.index');
    Route::post('/equipe', [TeamController::class, 'store'])->name('team.store');
    
    // 👤 Perfil
    Route::controller(ProfileController::class)->prefix('perfil')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::post('/avatar', 'uploadAvatar')->name('avatar');
        Route::post('/tema', 'updateTheme')->name('theme');
        Route::delete('/', 'destroy')->name('destroy');
    });

    //  Tarefas (SkyFlow REST)
    Route::controller(TodoController::class)->prefix('tarefas')->name('todos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/criar', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{todo}', 'show')->name('show');
        Route::get('/{todo}/editar', 'edit')->name('edit');
        Route::put('/{todo}', 'update')->name('update');
        Route::patch('/{todo}/alternar', 'toggle')->name('toggle');
        Route::delete('/{todo}', 'destroy')->name('destroy');

        Route::post('/{todo}/checklist', 'checklistStore')->name('checklist.store');
        Route::patch('/{todo}/checklist/{index}/alternar', 'checklistToggle')->name('checklist.toggle');
        Route::delete('/{todo}/checklist/{index}', 'checklistDestroy')->name('checklist.destroy');

        Route::post('/{todo}/comentarios', 'commentStore')->name('comments.store');

        Route::post('/{todo}/anexos', 'attachmentStore')->name('attachments.store');
        Route::delete('/{todo}/anexos/{index}', 'attachmentDestroy')->name('attachments.destroy');

        Route::post('/{todo}/etiquetas', 'labelStore')->name('labels.store');
        Route::delete('/{todo}/etiquetas/{index}', 'labelDestroy')->name('labels.destroy');
    });

    // 🏷️ Categorias
    Route::controller(CategoryController::class)->prefix('categorias')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/criar', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category}/editar', 'edit')->name('edit');
        Route::put('/{category}', 'update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });

    // 📝 Anotações
    Route::controller(NoteController::class)->prefix('anotacoes')->name('notes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/{note}', 'update')->name('update');
        Route::delete('/{note}', 'destroy')->name('destroy');
    });

    // 📅 Calendário
    Route::controller(CalendarController::class)->prefix('calendario')->name('calendar.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/eventos', 'events')->name('events');
        Route::patch('/reagendar', 'reschedule')->name('reschedule');
    });

    // 📋 Kanban
    Route::controller(KanbanController::class)->prefix('kanban')->name('kanban.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/colunas', 'columns')->name('columns');
        Route::post('/mover', 'move')->name('move');
        Route::post('/coluna/criar', 'storeColumn')->name('column.store');
        Route::post('/colunas/reorder', 'reorderColumns')->name('columns.reorder');
        Route::delete('/coluna/{columnKey}', 'deleteColumn')->name('column.delete');
        // Criação Rápida
        Route::post('/tarefa/criacao-rapida', 'quickCreate')->name('task.quick-create');
    });

    // ⚙️ Utilitários
    Route::post('/tarefas/alternar-visualizacao', [TodoController::class, 'toggleViewMode'])->name('todos.view-toggle');
    Route::get('/busca', [SearchController::class, 'global'])->name('search.global');
});

require __DIR__ . '/auth.php';