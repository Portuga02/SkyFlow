<!-- Sidebar -->
<aside
    class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-brand-100 flex flex-col z-40
    transform transition-transform duration-300 -translate-x-full md:translate-x-0"
    :class="mobileOpen ? 'translate-x-0' : ''">

    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-brand-50">
        <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-brand-600 text-white flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-cloud-bolt"></i>
            </div>
            <span class="font-bold text-brand-950">SkyFlow</span>
        </div>
        <button @click="mobileOpen = false" class="md:hidden text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Nav links -->
    <nav class="flex-1 overflow-y-auto scrollbar-thin px-3 py-5 space-y-6">

        <!-- Search Button -->
        <button @click="$dispatch('search-open'); mobileOpen = false"
            class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
            <i class="fa-solid fa-magnifying-glass w-4 text-center shrink-0"></i>
            <span class="flex-1 text-left text-xs sm:text-sm"><?php echo e(__('Buscar...')); ?></span>
            <kbd class="text-xs px-1.5 py-0.5 rounded bg-white border border-brand-200 hidden sm:inline">⌘K</kbd>
        </button>

        <div class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300"><?php echo e(__('Principal')); ?></p>

            <a href="<?php echo e(route('dashboard')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('dashboard') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-gauge-high w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Dashboard')); ?></span>
            </a>

            <a href="<?php echo e(route('todos.index')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('todos.*') && !request()->routeIs('todos.create') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-list-check w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Tarefas')); ?></span>
            </a>

            <a href="<?php echo e(route('todos.create')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('todos.create') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-circle-plus w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Nova Tarefa')); ?></span>
            </a>

            <a href="<?php echo e(route('kanban.index')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('kanban.*') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-grip w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Kanban')); ?></span>
            </a>
            <a href="<?php echo e(route('team.index')); ?>"
                class="flex items-center gap-3 px-3 py-2 mt-1 text-sm font-medium rounded-lg transition-colors <?php echo e(request()->routeIs('team.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100'); ?>">
                <i
                    class="fa-solid fa-users-gear w-5 text-center <?php echo e(request()->routeIs('team.*') ? 'text-blue-600' : 'text-gray-400'); ?>"></i>
                Minha Equipe
            </a>
        </div>

        <div class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300"><?php echo e(__('Organização')); ?>

            </p>

            <a href="<?php echo e(route('calendar.index')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('calendar.*') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-calendar w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Calendário')); ?></span>
            </a>

            <a href="<?php echo e(route('categories.index')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('categories.*') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-layer-group w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Categorias')); ?></span>
            </a>

            <a href="<?php echo e(route('notes.index')); ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition
                    <?php echo e(request()->routeIs('notes.*') ? 'bg-brand-100 text-brand-700' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-700'); ?>"
                @click="mobileOpen = false">
                <i class="fa-solid fa-note-sticky w-4 text-center shrink-0"></i>
                <span class="truncate"><?php echo e(__('Bloco de Notas')); ?></span>
            </a>
        </div>
    </nav>

    <!-- Footer User -->
    <div class="border-t border-brand-50 p-4" x-data="{ open: false }">
        <button @click="open = !open"
            class="w-full flex items-center gap-3 text-sm font-semibold text-brand-950 hover:text-brand-700">
            <?php if(Auth::user()->avatar_path): ?>
                <img src="<?php echo e(asset('storage/' . Auth::user()->avatar_path)); ?>" alt="<?php echo e(Auth::user()->name); ?>"
                    class="h-8 w-8 rounded-full object-cover">
            <?php else: ?>
                <div
                    class="h-8 w-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                </div>
            <?php endif; ?>
            <span class="flex-1 text-left truncate text-xs sm:text-sm"><?php echo e(Auth::user()->name); ?></span>
            <i class="fa-solid fa-chevron-down text-xs transition shrink-0" :class="open && 'rotate-180'"></i>
        </button>

        <div x-show="open" @click.outside="open = false" class="mt-2 space-y-1" x-transition x-cloak>
            <a href="<?php echo e(route('profile.edit')); ?>"
                class="block px-3 py-2 text-sm text-gray-600 hover:bg-brand-50 rounded-lg transition"
                @click="mobileOpen = false">
                <?php echo e(__('Perfil')); ?>

            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                <?php echo csrf_field(); ?>
                <button type="submit"
                    class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-rose-50 rounded-lg transition">
                    <?php echo e(__('Sair')); ?>

                </button>
            </form>
        </div>
    </div>
</aside>
<?php /**PATH C:\Workspace\SkyFlow\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>