<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2.5 text-start text-sm leading-5 font-medium text-slate-700 dark:text-slate-300 hover:bg-brand-50/80 dark:hover:bg-slate-700/60 hover:text-brand-600 dark:hover:text-brand-400 focus:outline-none focus:bg-brand-50 dark:focus:bg-slate-700/60 rounded-lg transition-colors duration-150']) }}>
    {{ $slot }}
</a>