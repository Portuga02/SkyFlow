<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'SkyFlow')); ?></title>

    <!-- Favicon & PWA -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#0071c4">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --brand-primary:
                <?php echo e(Auth::user()->theme_color ?? '#0071c4'); ?>

            ;
        }

        /* Efeito de hover dinâmico baseado na cor do usuário */
        .nav-item-hover:hover {
            background-color: color-mix(in srgb, var(--brand-primary) 10%, transparent);
            color: var(--brand-primary);
        }

        .nav-item-active {
            background-color: color-mix(in srgb, var(--brand-primary) 15%, transparent);
            color: var(--brand-primary);
            font-weight: 700;
        }
    </style>
</head>

<body class="font-figtree antialiased" x-data="{ mobileOpen: false }">
    <div class="min-h-screen bg-brand-50">

        <!-- Sidebar Overlay Mobile -->
        <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden"
            x-transition></div>

        <!-- Sidebar -->
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <main class="md:ml-64">
            <!-- Mobile Header Button -->
            <div class="md:hidden bg-white border-b border-brand-100 px-4 py-3 flex items-center gap-3">
                <button @click="mobileOpen = true" class="text-brand-600 hover:text-brand-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <span class="font-bold text-brand-950">SkyFlow</span>
            </div>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white border-b border-brand-100">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($slot); ?>

            </div>
        </main>
    </div>

    <!-- Search Modal (Global) -->
    <?php if (isset($component)) { $__componentOriginalcedca13ce10c75eb66af342dbaf4454a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcedca13ce10c75eb66af342dbaf4454a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcedca13ce10c75eb66af342dbaf4454a)): ?>
<?php $attributes = $__attributesOriginalcedca13ce10c75eb66af342dbaf4454a; ?>
<?php unset($__attributesOriginalcedca13ce10c75eb66af342dbaf4454a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcedca13ce10c75eb66af342dbaf4454a)): ?>
<?php $component = $__componentOriginalcedca13ce10c75eb66af342dbaf4454a; ?>
<?php unset($__componentOriginalcedca13ce10c75eb66af342dbaf4454a); ?>
<?php endif; ?>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js').catch(() => { });
            });
        }
    </script>
</body>

</html><?php /**PATH C:\Workspace\SkyFlow\resources\views\layouts\app.blade.php ENDPATH**/ ?>