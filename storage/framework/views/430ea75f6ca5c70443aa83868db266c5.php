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
        <div class="space-y-1">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-950"><?php echo e(__('Dashboard')); ?></h2>
            <p class="text-sm text-brand-600"><?php echo e(__('Bem-vindo de volta! Aqui está o seu resumo.')); ?></p>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-brand-600 to-brand-700 rounded-2xl shadow-card p-6 sm:p-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-3xl sm:text-4xl font-bold"><?php echo e(__('Olá')); ?>, <?php echo e(Auth::user()->name); ?>!</div>
                    <p class="text-brand-100 mt-2 text-sm sm:text-base"><?php echo e(Auth::user()->email); ?></p>
                </div>
                <?php if(Auth::user()->avatar_path): ?>
                    <img src="<?php echo e(asset('storage/' . Auth::user()->avatar_path)); ?>" alt="<?php echo e(Auth::user()->name); ?>"
                        class="h-20 w-20 rounded-full object-cover border-4 border-brand-500">
                <?php else: ?>
                    <div class="h-20 w-20 rounded-full bg-brand-500 text-white flex items-center justify-center text-3xl font-bold shrink-0">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <i class="fa-solid fa-list-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950"><?php echo e(Auth::user()->todos()->count()); ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e(__('Total de Tarefas')); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600 shrink-0">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950"><?php echo e(Auth::user()->todos()->where('is_completed', false)->count()); ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e(__('Pendentes')); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950"><?php echo e(Auth::user()->todos()->where('is_completed', true)->count()); ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e(__('Concluídas')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Account Table -->
        <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
            <div class="p-6 border-b border-brand-50">
                <h3 class="font-bold text-brand-950"><?php echo e(__('Minha Conta')); ?></h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-brand-500 uppercase bg-brand-50/60 hidden sm:table-header-group">
                        <tr>
                            <th class="px-6 py-3"><?php echo e(__('ID')); ?></th>
                            <th class="px-6 py-3"><?php echo e(__('Nome')); ?></th>
                            <th class="px-6 py-3"><?php echo e(__('E-mail')); ?></th>
                            <th class="px-6 py-3"><?php echo e(__('Ações')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-brand-50 hover:bg-brand-50/40 block sm:table-row cursor-pointer sm:cursor-default p-4 sm:p-0"
                            onclick="if (window.innerWidth < 640) window.location.href='<?php echo e(route('profile.edit')); ?>'">
                            <td class="px-6 py-4 font-medium text-brand-950 hidden sm:table-cell"><?php echo e(Auth::user()->id); ?></td>
                            <td class="px-6 py-4 block sm:table-cell font-semibold sm:font-normal">
                                <?php echo e(Auth::user()->name); ?>

                                <span class="block text-xs text-gray-400 sm:hidden mt-1"><?php echo e(Auth::user()->email); ?></span>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell"><?php echo e(Auth::user()->email); ?></td>
                            <td class="px-6 py-4 block sm:table-cell" onclick="event.stopPropagation()">
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('profile.edit')); ?>"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="<?php echo e(route('todos.index')); ?>"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-list text-xs"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('profile.destroy')); ?>" class="inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                                            onclick="return confirm('Tem certeza?')">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
<?php endif; ?><?php /**PATH C:\Workspace\SkyFlow\resources\views/dashboard.blade.php ENDPATH**/ ?>