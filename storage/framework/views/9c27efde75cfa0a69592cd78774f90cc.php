<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['iconOnly' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['iconOnly' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'flex items-center gap-2 select-none'])); ?>>
    <span class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-700 shadow-soft">
        <i class="fa-solid fa-cloud-bolt text-white text-base"></i>
    </span>
    <?php if (! ($iconOnly)): ?>
        <span class="text-lg font-extrabold tracking-tight text-brand-900">
            Sky<span class="text-brand-500">Flow</span>
        </span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\savio\Downloads\SkyFlow-master\resources\views/components/application-logo.blade.php ENDPATH**/ ?>