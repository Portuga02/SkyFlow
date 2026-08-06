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
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                <?php echo e(__('Detalhes da Tarefa')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="p-6 space-y-6">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Título da tarefa')); ?></span>
                            <h3 class="mt-1 text-xl font-bold text-brand-950"><?php echo e($todo->title); ?></h3>
                        </div>

                        <?php if($todo->is_completed): ?>
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                <i class="fa-solid fa-circle-check"></i> <?php echo e(__('Completo')); ?>

                            </span>
                        <?php else: ?>
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                <i class="fa-solid fa-hourglass-half"></i> <?php echo e(__('Pendente')); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Descrição da tarefa')); ?></span>
                        <p class="mt-1 text-gray-700 leading-relaxed"><?php echo e($todo->description); ?></p>
                    </div>

                    <div class="text-xs text-gray-400 flex items-center gap-4 pt-2 border-t border-brand-50">
                        <span><i class="fa-regular fa-clock mr-1"></i><?php echo e(__('Criada em')); ?> <?php echo e($todo->created_at->format('d/m/Y H:i')); ?></span>
                        <span><i class="fa-solid fa-rotate mr-1"></i><?php echo e(__('Atualizada em')); ?> <?php echo e($todo->updated_at->format('d/m/Y H:i')); ?></span>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="<?php echo e(route('todos.index')); ?>"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            <i class="fa-solid fa-arrow-rotate-left"></i> <?php echo e(__('Voltar')); ?>

                        </a>
                        <a href="<?php echo e(route('todos.edit', $todo->id)); ?>"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-soft transition">
                            <i class="fa-solid fa-pen"></i> <?php echo e(__('Editar tarefa')); ?>

                        </a>
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