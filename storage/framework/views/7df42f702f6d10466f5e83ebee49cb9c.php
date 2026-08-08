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
                    <?php echo e(__('Categorias')); ?>

                </h2>
                <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Organize suas tarefas em categorias e subcategorias.')); ?></p>
            </div>

            <a href="<?php echo e(route('categories.create')); ?>"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                <i class="fa-solid fa-circle-plus"></i>
                <?php echo e(__('Nova Categoria')); ?>

            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php if(session('alert-success')): ?>
                <div class="animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium"><?php echo e(session('alert-success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($categories->isEmpty()): ?>
                <!-- Empty state -->
                <div class="bg-white rounded-2xl shadow-card border border-brand-50 py-16 px-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-500">
                        <i class="fa-solid fa-layer-group text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950"><?php echo e(__('Nenhuma categoria criada ainda')); ?></h4>
                    <p class="mt-1 text-sm text-gray-500"><?php echo e(__('Crie categorias pra organizar melhor suas tarefas.')); ?></p>
                    <a href="<?php echo e(route('categories.create')); ?>"
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                        <i class="fa-solid fa-circle-plus"></i> <?php echo e(__('Criar minha primeira categoria')); ?>

                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                            <div class="p-5 flex items-start gap-4">
                                <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center text-white"
                                    style="background-color: <?php echo e($category->color); ?>">
                                    <i class="<?php echo e($category->icon); ?>"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-brand-950"><?php echo e($category->name); ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <?php echo e($category->todos_count); ?> <?php echo e(Str::plural('tarefa', $category->todos_count)); ?>

                                        <?php if($category->children->isNotEmpty()): ?>
                                            &middot; <?php echo e($category->children->count()); ?> <?php echo e(Str::plural('subcategoria', $category->children->count())); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="<?php echo e(route('categories.edit', $category->id)); ?>" title="<?php echo e(__('Editar categoria')); ?>"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('categories.destroy', $category->id)); ?>"
                                        onsubmit="return confirm('<?php echo e(__('Excluir esta categoria? As tarefas vinculadas ficarão sem categoria.')); ?>');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" title="<?php echo e(__('Excluir categoria')); ?>"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <?php if($category->children->isNotEmpty()): ?>
                                <div class="border-t border-brand-50 bg-brand-50/40 px-5 py-3 space-y-2">
                                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3">
                                            <span class="h-2 w-2 rounded-full" style="background-color: <?php echo e($child->color); ?>"></span>
                                            <span class="text-sm text-gray-600 flex-1"><?php echo e($child->name); ?></span>
                                            <span class="text-xs text-gray-400"><?php echo e($child->todos->count()); ?></span>
                                            <a href="<?php echo e(route('categories.edit', $child->id)); ?>" class="text-brand-400 hover:text-brand-600">
                                                <i class="fa-solid fa-pen text-[11px]"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/categories/index.blade.php ENDPATH**/ ?>