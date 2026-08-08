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

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                    <?php echo e(__('Minhas Tarefas')); ?>

                </h2>
                <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Organize seu fluxo e mantenha o foco no que importa.')); ?></p>
            </div>

            <a href="<?php echo e(route('todos.create')); ?>"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                <i class="fa-solid fa-circle-plus"></i>
                <?php echo e(__('Nova Tarefa')); ?>

            </a>

            <a href="<?php echo e(route('kanban.index')); ?>"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-600 text-sm font-semibold rounded-lg transition"
                title="<?php echo e(__('Ver em Kanban')); ?>">
                <i class="fa-solid fa-grip"></i>
                <span class="hidden sm:inline"><?php echo e(__('Kanban')); ?></span>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php if(session('alert-success')): ?>
                <div class="animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium"><?php echo e(session('alert-success')); ?></span>
                </div>
            <?php endif; ?>

            <?php
                $total = count($todoList);
                $completedCount = collect($todoList)->where('is_completed', true)->count();
                $pendingCount = $total - $completedCount;
                $progress = $total > 0 ? round(($completedCount / $total) * 100) : 0;
            ?>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600">
                        <i class="fa-solid fa-list-ul text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950"><?php echo e($total); ?></p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Total de tarefas')); ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950"><?php echo e($pendingCount); ?></p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Pendentes')); ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl font-extrabold text-brand-950"><?php echo e($completedCount); ?></p>
                            <span class="text-xs font-semibold text-emerald-600"><?php echo e($progress); ?>%</span>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Concluídas')); ?></p>
                        <div class="mt-1.5 h-1.5 w-full rounded-full bg-brand-50 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: <?php echo e($progress); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($total > 0): ?>
                <!-- Filter tabs (client-side, Alpine) -->
                <div x-data="{ filter: 'all' }" class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 pt-5">
                        <button @click="filter = 'all'"
                            :class="filter === 'all' ? 'bg-brand-600 text-white' : 'bg-brand-50 text-brand-600 hover:bg-brand-100'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition"><?php echo e(__('Todas')); ?></button>
                        <button @click="filter = 'pending'"
                            :class="filter === 'pending' ? 'bg-amber-500 text-white' : 'bg-brand-50 text-brand-600 hover:bg-brand-100'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition"><?php echo e(__('Pendentes')); ?></button>
                        <button @click="filter = 'done'"
                            :class="filter === 'done' ? 'bg-emerald-500 text-white' : 'bg-brand-50 text-brand-600 hover:bg-brand-100'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition"><?php echo e(__('Concluídas')); ?></button>
                    </div>

                    <ul class="p-5 space-y-3">
                        <?php $__currentLoopData = $todoList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todosLists): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li x-show="filter === 'all' || (filter === 'pending' && <?php echo e($todosLists->is_completed ? 'false' : 'true'); ?>) || (filter === 'done' && <?php echo e($todosLists->is_completed ? 'true' : 'false'); ?>)"
                                style="border-left-width: 4px; border-left-color: <?php echo e(['low' => '#10b981', 'medium' => '#f59e0b', 'high' => '#f43f5e'][$todosLists->priority ?? 'medium']); ?>;"
                                class="group animate-fade-in flex flex-col sm:flex-row sm:items-center gap-4 rounded-xl border <?php echo e($todosLists->is_completed ? 'border-emerald-100 bg-emerald-50/40' : 'border-brand-100 bg-white'); ?> p-4 hover:shadow-card-hover transition">

                                <!-- Toggle status -->
                                <form method="POST" action="<?php echo e(route('todos.toggle', $todosLists->id)); ?>" class="shrink-0">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                        title="<?php echo e($todosLists->is_completed ? __('Marcar como pendente') : __('Marcar como concluída')); ?>"
                                        class="flex h-8 w-8 items-center justify-center rounded-full border-2 transition
                                            <?php echo e($todosLists->is_completed ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-brand-300 text-transparent hover:border-brand-500'); ?>">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                </form>

                                <!-- Title & description -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-semibold text-brand-950 <?php echo e($todosLists->is_completed ? 'line-through text-brand-400' : ''); ?>">
                                            <?php echo e($todosLists->title); ?>

                                        </p>
                                        <?php $__currentLoopData = $todosLists->labels ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded-full" style="background-color: <?php echo e($label['color']); ?>">
                                                <?php echo e($label['name']); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate <?php echo e($todosLists->is_completed ? 'line-through' : ''); ?>">
                                        <?php echo e($todosLists->description); ?>

                                    </p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <?php if($todosLists->due_date): ?>
                                            <span class="text-[11px] font-semibold <?php echo e($todosLists->is_overdue ? 'text-rose-500' : 'text-gray-400'); ?>">
                                                <i class="fa-regular fa-calendar mr-1"></i><?php echo e($todosLists->due_date->format('d/m')); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if(count($todosLists->checklist ?? [])): ?>
                                            <span class="text-[11px] font-semibold text-gray-400">
                                                <i class="fa-solid fa-list-check mr-1"></i><?php echo e($todosLists->checklist_progress); ?>%
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Status pill -->
                                <div class="shrink-0">
                                    <?php if($todosLists->is_completed): ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                            <i class="fa-solid fa-circle-check"></i> <?php echo e(__('Completo')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                            <i class="fa-solid fa-hourglass-half"></i> <?php echo e(__('Pendente')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="<?php echo e(route('todos.show', $todosLists->id)); ?>" title="<?php echo e(__('Ver detalhes')); ?>"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('todos.edit', $todosLists->id)); ?>" title="<?php echo e(__('Editar tarefa')); ?>"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('todos.destroy', $todosLists->id)); ?>"
                                        onsubmit="return confirm('<?php echo e(__('Tem certeza que deseja excluir esta tarefa?')); ?>');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" title="<?php echo e(__('Excluir tarefa')); ?>"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Empty state -->
                <div class="bg-white rounded-2xl shadow-card border border-brand-50 py-16 px-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-500">
                        <i class="fa-solid fa-clipboard-list text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950"><?php echo e(__('Nenhuma tarefa por aqui ainda')); ?></h4>
                    <p class="mt-1 text-sm text-gray-500"><?php echo e(__('Que tal criar a primeira e organizar seu dia?')); ?></p>
                    <a href="<?php echo e(route('todos.create')); ?>"
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                        <i class="fa-solid fa-circle-plus"></i> <?php echo e(__('Criar minha primeira tarefa')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/auth/todo.blade.php ENDPATH**/ ?>