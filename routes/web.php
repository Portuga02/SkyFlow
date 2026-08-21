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

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/dashboard', function () {
    $user = Auth::user();

    // Estatísticas dos cards
    $stats = [
        'total'     => $user->todos()->count(),
        'pending'   => $user->todos()->where('status', '!=', 'done')->count(),
        'completed' => $user->todos()->where('status', 'done')->count(),
    ];

    // Tarefas Urgentes (Fogo no parquinho!)
    $urgentTodos = $user->todos()
        ->where('status', '!=', 'done')
        ->whereIn('priority', ['high', 'highest']) // Pega as altas
        ->orderByRaw('due_date IS NULL, due_date ASC') // Ordena por data
        ->take(5)
        ->get();

    // Últimas Anotações
    $recentNotes = \App\Models\Note::where('user_id', $user->id)
        ->latest()
        ->take(3)
        ->get();

    // Para o gráfico de categorias (ex: SkyCast Pro, SkyMaps, etc)
    $categories = \App\Models\Category::where('user_id', $user->id)
        ->withCount(['todos' => function ($query) {
            $query->where('status', '!=', 'done');
        }])
        ->orderByDesc('todos_count')
        ->take(4)
        ->get();

    return view('dashboard', compact('stats', 'urgentTodos', 'recentNotes', 'categories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team', [TeamController::class, 'store'])->name('team.store');
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

   
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::put('/{category}', 'update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });
    Route::controller(NoteController::class)->prefix('notes')->name('notes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/{note}', 'update')->name('update');
        Route::delete('/{note}', 'destroy')->name('destroy');
    });
    Route::controller(CalendarController::class)->prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/events', 'events')->name('events');
        Route::patch('/reschedule', 'reschedule')->name('reschedule');
    });

   Route::controller(KanbanController::class)->prefix('kanban')->name('kanban.')->group(function () {
        Route::get('/', 'index')->name('index'); 
        Route::get('/columns', 'columns')->name('columns'); 
        Route::post('/move', 'move')->name('move');

        Route::post('/column/create', 'storeColumn')->name('column.store');

        Route::delete('/column/{columnKey}', 'deleteColumn')->name('column.delete');
    });

    Route::post('/todos/view-toggle', [TodoController::class, 'toggleViewMode'])->name('todos.view-toggle');

    Route::get('/search', [SearchController::class, 'global'])->name('search.global');
});

require __DIR__ . '/auth.php';
