<?php
    $navItem = function (string $route, string $icon, string $label, bool $soon = false) {
        return compact('route', 'icon', 'label', 'soon');
    };
?>

<aside x-data="{ mobileOpen: false }" class="lg:w-64 shrink-0">

    <!-- Mobile top bar -->
    <div class="lg:hidden flex items-center justify-between px-4 h-16 bg-white/80 backdrop-blur border-b border-brand-100 sticky top-0 z-40">
        <a href="<?php echo e(route('dashboard')); ?>"><?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?></a>
        <button @click="mobileOpen = true" class="flex h-9 w-9 items-center justify-center rounded-lg text-brand-600 hover:bg-brand-50">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Mobile overlay -->
    <div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
        class="lg:hidden fixed inset-0 bg-brand-950/40 backdrop-blur-sm z-50"></div>

    <!-- Sidebar panel -->
    <div x-cloak
        :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:sticky top-0 left-0 h-screen w-72 lg:w-64 bg-white border-r border-brand-100 z-50 flex flex-col transition-transform duration-200 ease-in-out">

        <!-- Brand + close (mobile) -->
        <div class="flex items-center justify-between h-16 px-5 border-b border-brand-50">
            <a href="<?php echo e(route('dashboard')); ?>"><?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?></a>
            <button @click="mobileOpen = false" class="lg:hidden flex h-8 w-8 items-center justify-center rounded-lg text-brand-400 hover:bg-brand-50">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Nav links -->
        <nav class="flex-1 overflow-y-auto scrollbar-thin px-3 py-5 space-y-6">

            <div class="space-y-1">
                <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300"><?php echo e(__('Principal')); ?></p>

                <a href="<?php echo e(route('dashboard')); ?>"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                        <?php echo e(request()->routeIs('dashboard') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>">
                    <i class="fa-solid fa-gauge-high w-4 text-center"></i> <?php echo e(__('Dashboard')); ?>

                </a>

                <a href="<?php echo e(route('todos.index')); ?>"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                        <?php echo e(request()->routeIs('todos.index') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>">
                    <i class="fa-solid fa-list-check w-4 text-center"></i> <?php echo e(__('Tarefas')); ?>

                </a>

                <a href="<?php echo e(route('todos.create')); ?>"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                        <?php echo e(request()->routeIs('todos.create') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>">
                    <i class="fa-solid fa-circle-plus w-4 text-center"></i> <?php echo e(__('Nova Tarefa')); ?>

                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300"><?php echo e(__('Organização')); ?></p>

                <span class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-400 cursor-not-allowed select-none">
                    <span class="flex items-center gap-3"><i class="fa-solid fa-layer-group w-4 text-center"></i> <?php echo e(__('Categorias')); ?></span>
                    <span class="text-[10px] font-bold uppercase bg-brand-50 text-brand-400 px-2 py-0.5 rounded-full"><?php echo e(__('em breve')); ?></span>
                </span>

                <span class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-400 cursor-not-allowed select-none">
                    <span class="flex items-center gap-3"><i class="fa-solid fa-table-columns w-4 text-center"></i> <?php echo e(__('Kanban')); ?></span>
                    <span class="text-[10px] font-bold uppercase bg-brand-50 text-brand-400 px-2 py-0.5 rounded-full"><?php echo e(__('em breve')); ?></span>
                </span>
            </div>
        </nav>

        <!-- User footer -->
        <div class="border-t border-brand-50 p-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-brand-50 transition text-left">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white text-sm font-bold">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-brand-950 truncate"><?php echo e(Auth::user()->name); ?></span>
                        <span class="block text-xs text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></span>
                    </span>
                    <i class="fa-solid fa-chevron-up text-xs text-brand-300"></i>
                </button>

                <div x-show="open" x-cloak x-transition
                    class="absolute bottom-full mb-2 left-0 right-0 bg-white rounded-lg shadow-card-hover border border-brand-100 overflow-hidden">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                        <i class="fa-solid fa-user-gear w-4 text-center text-brand-500"></i> <?php echo e(__('Perfil')); ?>

                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center text-brand-500"></i> <?php echo e(__('Sair')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside><?php /**PATH C:\Workspace\SkyFlow\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>