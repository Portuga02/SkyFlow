<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BANNER DE BOAS-VINDAS -->
            <div
                class="bg-gradient-to-r from-brand-600 to-blue-500 rounded-2xl p-8 text-white shadow-lg flex items-center justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold mb-2"><?php echo e(__('Olá, ')); ?><?php echo e(Auth::user()->name); ?>! 🚀</h2>
                    <p class="text-blue-100 text-sm md:text-base">
                        <?php echo e(__('Aqui está o resumo do seu fluxo de trabalho de hoje.')); ?></p>
                </div>
                <div
                    class="hidden md:flex h-16 w-16 bg-white/20 backdrop-blur-sm rounded-full items-center justify-center text-3xl font-bold shadow-inner relative z-10">
                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                </div>
                <!-- Efeito visual de fundo -->
                <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <!-- AÇÕES RÁPIDAS -->
            <div class="flex gap-4 overflow-x-auto pb-2">
                <a href="<?php echo e(route('todos.create')); ?>"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-circle-plus text-brand-600"></i> Nova Tarefa
                </a>
                <a href="<?php echo e(route('kanban.index')); ?>"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-table-columns text-amber-500"></i> Abrir Kanban
                </a>
                <button onclick="createQuickNote()"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-note-sticky text-emerald-500"></i> Nota Rápida
                </button>
            </div>

            <!-- ESTATÍSTICAS (CARDS) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase"><?php echo e(__('Total de Tarefas')); ?></p>
                        <h3 class="text-2xl font-black text-brand-950"><?php echo e($stats['total']); ?></h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase"><?php echo e(__('Pendentes')); ?></p>
                        <h3 class="text-2xl font-black text-brand-950"><?php echo e($stats['pending']); ?></h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase"><?php echo e(__('Concluídas')); ?></p>
                        <h3 class="text-2xl font-black text-brand-950"><?php echo e($stats['completed']); ?></h3>
                    </div>
                </div>
            </div>

            <!-- GRID PRINCIPAL (URGÊNCIAS E CATEGORIAS/NOTAS) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LADO ESQUERDO: Tarefas Urgentes (Fogo no Parquinho) -->
                <div
                    class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-brand-50 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-brand-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-extrabold text-lg text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-fire text-rose-500"></i> Fogo no Parquinho (Urgentes)
                        </h3>
                        <a href="<?php echo e(route('todos.index')); ?>"
                            class="text-sm font-semibold text-brand-600 hover:text-brand-800">Ver todas</a>
                    </div>

                    <div class="p-6 flex-1">
                        <?php if($urgentTodos->isEmpty()): ?>
                            <div class="h-full flex flex-col items-center justify-center text-center space-y-3 py-10">
                                <div
                                    class="h-16 w-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-2xl">
                                    <i class="fa-solid fa-mug-hot"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-brand-950">Tudo sob controle!</h4>
                                    <p class="text-sm text-gray-500">Não há tarefas de alta prioridade pendentes no
                                        momento.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $urgentTodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                        class="group flex items-start gap-4 p-4 rounded-xl border border-brand-50 hover:border-brand-200 hover:shadow-md transition bg-white">
                                        <!-- Botão AJAX para concluir a tarefa -->
                                        <button onclick="markAsDone(<?php echo e($todo->id); ?>)" title="Marcar como Concluída"
                                            class="focus:outline-none group/btn mt-1 flex-shrink-0 cursor-pointer">
                                            <i
                                                class="fa-regular fa-circle text-gray-300 text-xl group-hover/btn:hidden"></i>
                                            <i
                                                class="fa-solid fa-circle-check text-emerald-500 text-xl hidden group-hover/btn:block transition"></i>
                                        </button>
                                        <div class="flex-1 min-w-0">
                                            <a href="<?php echo e(route('todos.show', $todo->id)); ?>"
                                                class="block font-bold text-brand-950 truncate hover:text-brand-600 transition">
                                                <?php echo e($todo->title); ?>

                                            </a>
                                            <div class="flex items-center gap-3 mt-1.5">
                                                <?php if($todo->due_date): ?>
                                                    <span
                                                        class="text-xs font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-600">
                                                        <i class="fa-regular fa-calendar mr-1"></i>
                                                        <?php echo e(\Carbon\Carbon::parse($todo->due_date)->format('d/m/Y H:i')); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php if($todo->category): ?>
                                                    <span class="text-xs font-semibold px-2 py-0.5 rounded text-white"
                                                        style="background-color: <?php echo e($todo->category->color); ?>">
                                                        <?php echo e($todo->category->name); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- LADO DIREITO: Distribuição de Projetos e Notas -->
                <div class="space-y-6">

                    <!-- Foco por Projetos (Ex: SkyRadar, SkyMaps, etc) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-blue-500"></i> Foco Atual
                        </h3>

                        <?php if(isset($categories) && $categories->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <div class="flex justify-between text-sm font-bold mb-1">
                                            <span class="text-brand-950"><?php echo e($category->name); ?></span>
                                            <span class="text-gray-500"><?php echo e($category->todos_count); ?> pendentes</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                                            <!-- Calcula uma porcentagem fictícia baseada no total de pendentes gerais para gerar a barra -->
                                            <?php
                                                $percent =
                                                    $stats['pending'] > 0
                                                        ? ($category->todos_count / $stats['pending']) * 100
                                                        : 0;
                                            ?>
                                            <div class="h-2.5 rounded-full"
                                                style="width: <?php echo e($percent); ?>%; background-color: <?php echo e($category->color); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 text-center py-4">Nenhuma categoria ativa no momento.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Post-its (Últimas Notas) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-thumbtack text-amber-500"></i> Anotações Recentes
                        </h3>

                        <?php if(isset($recentNotes) && $recentNotes->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $recentNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg shadow-sm">
                                        <h4 class="font-bold text-sm text-amber-900 truncate"><?php echo e($note->title); ?></h4>
                                        <p class="text-xs text-amber-700 mt-1 line-clamp-2"><?php echo e($note->content); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 text-center py-4">O bloco de notas está vazio.</p>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        function markAsDone(id) {
            // Envia a ordem para a API do Kanban mudar o status para 'done'
            fetch('/kanban/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        id: id,
                        status: 'done' // O slug da sua coluna de Concluído
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Se deu certo, recarrega a página suavemente para atualizar os gráficos
                        location.reload();
                    } else {
                        alert('Ocorreu um erro ao concluir a tarefa.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Falha na comunicação com o servidor.');
                });
        }

        function createQuickNote() {
            // Faz uma requisição silenciosa para criar a nota
            fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // Pede para o Laravel não tentar redirecionar na marra
                    },
                    body: JSON.stringify({
                        title: 'Nova Nota',
                        content: 'Clique para editar...',
                        color: '#fef08a' // Amarelinho padrão
                    })
                })
                .then(() => {
                    // Redireciona você direto pro Bloco de Notas!
                    window.location.href = '/notes';
                })
                .catch(err => {
                    console.error('Erro ao criar nota:', err);
                    window.location.href = '/notes';
                });
    }
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Workspace\SkyFlow\resources\views/dashboard.blade.php ENDPATH**/ ?>