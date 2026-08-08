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
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight"><?php echo e(__('Kanban')); ?></h2>
                <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Arraste as tarefas entre colunas para mudar o status.')); ?></p>
            </div>
            <form method="POST" action="<?php echo e(route('todos.view-toggle')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-600 text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-list"></i> <?php echo e(__('Voltar para Lista')); ?>

                </button>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="kanban()">
                <!-- Coluna A FAZER -->
                <div class="flex flex-col">
                    <div class="mb-4">
                        <h3 class="font-bold text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-circle-notch text-amber-500"></i> <?php echo e(__('A Fazer')); ?>

                            <span class="ml-auto inline-flex items-center justify-center h-6 w-6 rounded-full bg-amber-100 text-amber-700 text-xs font-bold" x-text="todos.filter(t => t.status === 'todo').length"></span>
                        </h3>
                    </div>
                    <div class="kanban-column flex-1 min-h-96 space-y-3 p-3 bg-amber-50/40 rounded-xl border-2 border-dashed border-amber-200" data-status="todo">
                        <?php $__currentLoopData = $todos->where('status', 'todo'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="kanban-card bg-white rounded-lg shadow-sm border-l-4 border-amber-500 p-4 cursor-grab active:cursor-grabbing" draggable="true" data-id="<?php echo e($todo->id); ?>">
                                <p class="font-semibold text-sm text-brand-950"><?php echo e($todo->title); ?></p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($todo->description); ?></p>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <?php if($todo->priority === 'high'): ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-rose-500 px-2 py-0.5 rounded"><?php echo e(__('Alta')); ?></span>
                                    <?php elseif($todo->priority === 'medium'): ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-amber-500 px-2 py-0.5 rounded"><?php echo e(__('Média')); ?></span>
                                    <?php else: ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-emerald-500 px-2 py-0.5 rounded"><?php echo e(__('Baixa')); ?></span>
                                    <?php endif; ?>
                                    <?php if($todo->category): ?>
                                        <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded" style="background-color: <?php echo e($todo->category->color); ?>"><?php echo e($todo->category->name); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-2 text-right">
                                    <a href="<?php echo e(route('todos.show', $todo->id)); ?>" class="text-[11px] font-semibold text-brand-500 hover:text-brand-700"><i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Coluna EM ANDAMENTO -->
                <div class="flex flex-col">
                    <div class="mb-4">
                        <h3 class="font-bold text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-circle-play text-blue-500"></i> <?php echo e(__('Em Andamento')); ?>

                            <span class="ml-auto inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold" x-text="todos.filter(t => t.status === 'in_progress').length"></span>
                        </h3>
                    </div>
                    <div class="kanban-column flex-1 min-h-96 space-y-3 p-3 bg-blue-50/40 rounded-xl border-2 border-dashed border-blue-200" data-status="in_progress">
                        <?php $__currentLoopData = $todos->where('status', 'in_progress'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="kanban-card bg-white rounded-lg shadow-sm border-l-4 border-blue-500 p-4 cursor-grab active:cursor-grabbing" draggable="true" data-id="<?php echo e($todo->id); ?>">
                                <p class="font-semibold text-sm text-brand-950"><?php echo e($todo->title); ?></p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($todo->description); ?></p>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <?php if($todo->priority === 'high'): ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-rose-500 px-2 py-0.5 rounded"><?php echo e(__('Alta')); ?></span>
                                    <?php elseif($todo->priority === 'medium'): ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-amber-500 px-2 py-0.5 rounded"><?php echo e(__('Média')); ?></span>
                                    <?php else: ?>
                                        <span class="inline-flex text-[10px] font-bold text-white bg-emerald-500 px-2 py-0.5 rounded"><?php echo e(__('Baixa')); ?></span>
                                    <?php endif; ?>
                                    <?php if($todo->category): ?>
                                        <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded" style="background-color: <?php echo e($todo->category->color); ?>"><?php echo e($todo->category->name); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-2 text-right">
                                    <a href="<?php echo e(route('todos.show', $todo->id)); ?>" class="text-[11px] font-semibold text-brand-500 hover:text-brand-700"><i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Coluna CONCLUÍDO -->
                <div class="flex flex-col">
                    <div class="mb-4">
                        <h3 class="font-bold text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i> <?php echo e(__('Concluído')); ?>

                            <span class="ml-auto inline-flex items-center justify-center h-6 w-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold" x-text="todos.filter(t => t.status === 'done').length"></span>
                        </h3>
                    </div>
                    <div class="kanban-column flex-1 min-h-96 space-y-3 p-3 bg-emerald-50/40 rounded-xl border-2 border-dashed border-emerald-200" data-status="done">
                        <?php $__currentLoopData = $todos->where('status', 'done'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="kanban-card bg-white rounded-lg shadow-sm border-l-4 border-emerald-500 p-4 cursor-grab active:cursor-grabbing opacity-75" draggable="true" data-id="<?php echo e($todo->id); ?>">
                                <p class="font-semibold text-sm text-gray-500 line-through"><?php echo e($todo->title); ?></p>
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2"><?php echo e($todo->description); ?></p>
                                <div class="mt-2 text-right">
                                    <a href="<?php echo e(route('todos.show', $todo->id)); ?>" class="text-[11px] font-semibold text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function kanban() {
            return {
                todos: <?php echo json_encode($todos, 15, 512) ?>,
                draggedCard: null,
                init() {
                    this.attachDragListeners();
                },
                attachDragListeners() {
                    document.addEventListener('dragstart', (e) => {
                        if (e.target.classList.contains('kanban-card')) {
                            this.draggedCard = e.target;
                            e.target.style.opacity = '0.5';
                        }
                    });
                    document.addEventListener('dragend', (e) => {
                        if (e.target.classList.contains('kanban-card')) {
                            e.target.style.opacity = '1';
                            this.draggedCard = null;
                        }
                    });
                    document.addEventListener('dragover', (e) => e.preventDefault());
                    document.addEventListener('drop', (e) => {
                        if (e.target.classList.contains('kanban-column') && this.draggedCard) {
                            e.preventDefault();
                            const targetStatus = e.target.dataset.status;
                            const cardId = this.draggedCard.dataset.id;
                            this.moveCard(cardId, targetStatus, e.target);
                        }
                    });
                },
                moveCard(cardId, newStatus, targetColumn) {
                    fetch('/kanban/move', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ id: cardId, status: newStatus }),
                    })
                    .then(r => r.json())
                    .then(() => {
                        targetColumn.appendChild(this.draggedCard);
                        console.log('Card movido com sucesso');
                    })
                    .catch(err => {
                        console.error('Erro:', err);
                        alert('Erro ao mover a tarefa');
                    });
                }
            }
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/kanban/index.blade.php ENDPATH**/ ?>