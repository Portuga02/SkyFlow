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
                <?php echo e(__('Dashboard')); ?>

            </h2>
            <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Bem-vindo de volta! Aqui está o seu resumo.')); ?></p>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php
                $total = \App\Models\Todo::count();
                $completedCount = \App\Models\Todo::where('is_completed', true)->count();
                $pendingCount = $total - $completedCount;
            ?>

            <!-- Boas vindas -->
            <div class="rounded-2xl bg-gradient-to-r from-brand-700 to-brand-500 p-6 sm:p-8 text-white shadow-card relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
                <div class="absolute right-16 bottom-0 h-20 w-20 rounded-full bg-white/10"></div>
                <div class="relative flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-2xl font-bold">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </div>
                    <div>
                        <p class="text-lg font-bold"><?php echo e(__('Olá, :name!', ['name' => Auth::user()->name])); ?></p>
                        <p class="text-sm text-brand-100"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                </div>
            </div>

            <!-- Stats -->
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
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950"><?php echo e($completedCount); ?></p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400"><?php echo e(__('Concluídas')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Conta -->
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="px-6 py-5 border-b border-brand-50">
                    <h3 class="font-bold text-brand-950"><?php echo e(__('Minha Conta')); ?></h3>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-brand-500 uppercase bg-brand-50/60">
                            <tr>
                                <th scope="col" class="px-6 py-3"><?php echo e(__('ID')); ?></th>
                                <th scope="col" class="px-6 py-3"><?php echo e(__('Nome')); ?></th>
                                <th scope="col" class="px-6 py-3"><?php echo e(__('E-mail')); ?></th>
                                <th scope="col" class="px-6 py-3 text-center"><?php echo e(__('Ações')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b border-brand-50 hover:bg-brand-50/40">
                                <td class="px-6 py-4 font-medium text-brand-950"><?php echo e(Auth::user()->id); ?></td>
                                <td class="px-6 py-4"><?php echo e(Auth::user()->name); ?></td>
                                <td class="px-6 py-4"><?php echo e(Auth::user()->email); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="<?php echo e(route('profile.edit')); ?>"
                                           class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition"
                                           title="Ver meu perfil">
                                            <i class="fa-solid fa-circle-user"></i>
                                        </a>

                                        <a href="<?php echo e(route('todos.index')); ?>"
                                           class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition"
                                           title="Ir para suas listas de tarefas">
                                            <i class="fa-solid fa-list-check"></i>
                                        </a>

                                        <form action="<?php echo e(route('profile.destroy')); ?>" method="POST" class="inline-block m-0">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                                                    title="Excluir este usuário"
                                                    onclick="return confirm('Tem certeza que deseja excluir sua conta?');">
                                                <i class="fa-solid fa-trash"></i>
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
<?php /**PATH C:\Users\savio\Downloads\SkyFlow-master\resources\views/dashboard.blade.php ENDPATH**/ ?>