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
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="<?php echo e(route('todos.index')); ?>" class="text-xs font-semibold text-brand-500 hover:text-brand-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> <?php echo e(__('Voltar para tarefas')); ?>

                </a>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight mt-1">
                    <?php echo e($todo->title); ?>

                </h2>
            </div>
            <a href="<?php echo e(route('todos.edit', $todo->id)); ?>"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                <i class="fa-solid fa-pen"></i> <?php echo e(__('Editar')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <?php if(session('alert-success')): ?>
                <div class="mb-6 animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium"><?php echo e(session('alert-success')); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Coluna principal -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Status + Descrição -->
                    <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6 space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <?php if($todo->is_completed): ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                    <i class="fa-solid fa-circle-check"></i> <?php echo e(__('Completo')); ?>

                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                    <i class="fa-solid fa-hourglass-half"></i> <?php echo e(__('Pendente')); ?>

                                </span>
                            <?php endif; ?>

                            <?php
                                $priorityMap = [
                                    'high'   => ['label' => 'Alta', 'class' => 'bg-rose-100 text-rose-700'],
                                    'medium' => ['label' => 'Média', 'class' => 'bg-amber-100 text-amber-700'],
                                    'low'    => ['label' => 'Baixa', 'class' => 'bg-emerald-100 text-emerald-700'],
                                ];
                                $p = $priorityMap[$todo->priority ?? 'medium'];
                            ?>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold <?php echo e($p['class']); ?>">
                                <i class="fa-solid fa-flag"></i> <?php echo e(__('Prioridade')); ?> <?php echo e($p['label']); ?>

                            </span>

                            <?php if($todo->due_date): ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold
                                    <?php echo e($todo->is_overdue ? 'bg-rose-100 text-rose-700' : 'bg-brand-50 text-brand-600'); ?>">
                                    <i class="fa-regular fa-calendar"></i> <?php echo e($todo->due_date->format('d/m/Y')); ?>

                                    <?php if($todo->is_overdue): ?> &middot; <?php echo e(__('atrasada')); ?> <?php endif; ?>
                                </span>
                            <?php endif; ?>

                            <?php if($todo->category): ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold text-white"
                                    style="background-color: <?php echo e($todo->category->color); ?>">
                                    <i class="<?php echo e($todo->category->icon); ?>"></i> <?php echo e($todo->category->name); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Etiquetas -->
                        <div x-data="{ adding: false }" class="flex flex-wrap items-center gap-2">
                            <?php $__currentLoopData = $todo->labels ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold text-white"
                                    style="background-color: <?php echo e($label['color']); ?>">
                                    <?php echo e($label['name']); ?>

                                    <form method="POST" action="<?php echo e(route('todos.labels.destroy', [$todo->id, $i])); ?>" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="opacity-70 hover:opacity-100"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <button @click="adding = !adding" class="text-xs font-semibold text-brand-500 hover:text-brand-700 border border-dashed border-brand-300 rounded-full px-3 py-1">
                                <i class="fa-solid fa-plus"></i> <?php echo e(__('Etiqueta')); ?>

                            </button>

                            <form x-show="adding" x-cloak method="POST" action="<?php echo e(route('todos.labels.store', $todo->id)); ?>"
                                class="flex items-center gap-2 mt-2 w-full" x-data="{ color: '#0c8fe6' }">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="name" required maxlength="30" placeholder="<?php echo e(__('Nome da etiqueta')); ?>"
                                    class="text-sm rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 flex-1">
                                <input type="color" name="color" x-model="color" class="h-9 w-12 rounded-lg border border-brand-200 p-1">
                                <button type="submit" class="px-3 py-2 rounded-lg bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700"><?php echo e(__('Add')); ?></button>
                            </form>
                        </div>

                        <div class="border-t border-brand-50 pt-4">
                            <span class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Descrição')); ?></span>
                            <p class="mt-1 text-gray-700 leading-relaxed"><?php echo e($todo->description ?: __('Sem descrição.')); ?></p>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                        <?php
                            $checklist = $todo->checklist ?? [];
                            $progress = $todo->checklist_progress;
                        ?>

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-brand-950 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-brand-500"></i> <?php echo e(__('Checklist')); ?>

                            </h3>
                            <span class="text-xs font-semibold text-brand-500"><?php echo e($progress); ?>%</span>
                        </div>

                        <?php if(count($checklist)): ?>
                            <div class="h-1.5 w-full rounded-full bg-brand-50 overflow-hidden mb-4">
                                <div class="h-full rounded-full bg-brand-500 transition-all" style="width: <?php echo e($progress); ?>%"></div>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <?php $__empty_1 = true; $__currentLoopData = $checklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center gap-3 group">
                                    <form method="POST" action="<?php echo e(route('todos.checklist.toggle', [$todo->id, $i])); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button type="submit"
                                            class="flex h-6 w-6 items-center justify-center rounded-md border-2 transition shrink-0
                                                <?php echo e(($item['done'] ?? false) ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-brand-300 text-transparent hover:border-brand-500'); ?>">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                    <span class="flex-1 text-sm text-gray-700 <?php echo e(($item['done'] ?? false) ? 'line-through text-gray-400' : ''); ?>">
                                        <?php echo e($item['text']); ?>

                                    </span>
                                    <form method="POST" action="<?php echo e(route('todos.checklist.destroy', [$todo->id, $i])); ?>"
                                        class="opacity-0 group-hover:opacity-100 transition">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-gray-300 hover:text-rose-500"><i class="fa-solid fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-gray-400"><?php echo e(__('Nenhum item ainda. Adicione o primeiro passo abaixo.')); ?></p>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="<?php echo e(route('todos.checklist.store', $todo->id)); ?>" class="flex items-center gap-2 mt-4">
                            <?php echo csrf_field(); ?>
                            <input type="text" name="text" required maxlength="255" placeholder="<?php echo e(__('Adicionar item ao checklist...')); ?>"
                                class="flex-1 text-sm rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-50 text-brand-600 text-sm font-semibold hover:bg-brand-100">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Anexos -->
                    <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                        <h3 class="font-bold text-brand-950 flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-paperclip text-brand-500"></i> <?php echo e(__('Anexos')); ?>

                        </h3>

                        <div class="space-y-2 mb-4">
                            <?php $__empty_1 = true; $__currentLoopData = $todo->attachments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center gap-3 rounded-lg border border-brand-50 px-3 py-2">
                                    <i class="fa-solid fa-file text-brand-400"></i>
                                    <a href="<?php echo e(asset('storage/' . $file['path'])); ?>" target="_blank"
                                        class="flex-1 text-sm text-brand-700 hover:underline truncate"><?php echo e($file['name']); ?></a>
                                    <form method="POST" action="<?php echo e(route('todos.attachments.destroy', [$todo->id, $i])); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-gray-300 hover:text-rose-500"><i class="fa-solid fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-gray-400"><?php echo e(__('Nenhum anexo enviado ainda.')); ?></p>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="<?php echo e(route('todos.attachments.store', $todo->id)); ?>" enctype="multipart/form-data" class="flex items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="file" name="file" required
                                class="flex-1 text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-600 file:text-sm file:font-semibold hover:file:bg-brand-100">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-50 text-brand-600 text-sm font-semibold hover:bg-brand-100">
                                <i class="fa-solid fa-upload"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Comentários -->
                    <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                        <h3 class="font-bold text-brand-950 flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-comments text-brand-500"></i> <?php echo e(__('Comentários')); ?>

                        </h3>

                        <div class="space-y-3 mb-4">
                            <?php $__empty_1 = true; $__currentLoopData = array_reverse($todo->comments ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white text-xs font-bold">
                                        <?php echo e(strtoupper(substr($comment['user'], 0, 1))); ?>

                                    </span>
                                    <div class="flex-1 bg-brand-50/60 rounded-lg px-3 py-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-brand-900"><?php echo e($comment['user']); ?></span>
                                            <span class="text-[11px] text-gray-400"><?php echo e(\Carbon\Carbon::parse($comment['at'])->diffForHumans()); ?></span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-0.5"><?php echo e($comment['body']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-gray-400"><?php echo e(__('Seja o primeiro a comentar.')); ?></p>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="<?php echo e(route('todos.comments.store', $todo->id)); ?>" class="flex items-start gap-2">
                            <?php echo csrf_field(); ?>
                            <textarea name="body" required rows="2" placeholder="<?php echo e(__('Escreva um comentário...')); ?>"
                                class="flex-1 text-sm rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500"></textarea>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 h-fit">
                                <?php echo e(__('Enviar')); ?>

                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar de detalhes -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6 space-y-4">
                        <h3 class="font-bold text-brand-950 text-sm"><?php echo e(__('Detalhes')); ?></h3>

                        <div>
                            <span class="text-xs text-gray-400"><?php echo e(__('Responsável')); ?></span>
                            <div class="flex items-center gap-2 mt-1">
                                <?php if($todo->assignee): ?>
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-600 text-white text-xs font-bold">
                                        <?php echo e(strtoupper(substr($todo->assignee->name, 0, 1))); ?>

                                    </span>
                                    <span class="text-sm font-semibold text-brand-950"><?php echo e($todo->assignee->name); ?></span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400"><?php echo e(__('Ninguém atribuído')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="border-t border-brand-50 pt-3 text-xs text-gray-400 space-y-1">
                            <p><i class="fa-regular fa-clock mr-1"></i><?php echo e(__('Criada em')); ?> <?php echo e($todo->created_at->format('d/m/Y H:i')); ?></p>
                            <p><i class="fa-solid fa-rotate mr-1"></i><?php echo e(__('Atualizada em')); ?> <?php echo e($todo->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>

                        <form method="POST" action="<?php echo e(route('todos.destroy', $todo->id)); ?>"
                            onsubmit="return confirm('<?php echo e(__('Tem certeza que deseja excluir esta tarefa?')); ?>');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
                                <i class="fa-solid fa-trash"></i> <?php echo e(__('Excluir tarefa')); ?>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/auth/showTodo.blade.php ENDPATH**/ ?>