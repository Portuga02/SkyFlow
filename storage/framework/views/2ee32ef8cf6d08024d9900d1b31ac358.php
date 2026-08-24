<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'SkyFlow')); ?></title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased text-slate-900 bg-gradient-to-b from-brand-600 to-brand-900 min-h-screen">
 
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:px-6">

        <div class="mb-6">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="h-11 w-11 rounded-2xl bg-white text-brand-600 flex items-center justify-center text-xl font-bold shadow-lg group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-cloud-bolt"></i>
                </div>
                <span class="font-extrabold text-2xl text-white tracking-tight">SkyFlow</span>
            </a>
        </div>
        <div class="w-full max-w-[420px] bg-white p-6 sm:p-8 rounded-3xl shadow-2xl border border-white/20 backdrop-blur-xs">
            <?php echo e($slot); ?>

        </div>

        <!-- Rodapé -->
        <p class="mt-8 text-center text-xs text-brand-100 font-medium opacity-80">
            &copy; <?php echo e(date('Y')); ?> SkyFlow. Todos os direitos reservados.
        </p>
    </div>

</body>
</html><?php /**PATH C:\Workspace\SkyFlow\resources\views/layouts/guest.blade.php ENDPATH**/ ?>