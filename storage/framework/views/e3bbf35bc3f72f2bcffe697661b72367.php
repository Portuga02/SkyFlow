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
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight"><?php echo e(__('Bloco de Notas')); ?></h2>
                <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Suas anotações rápidas, salvas automaticamente.')); ?></p>
            </div>
            <form method="POST" action="<?php echo e(route('notes.store')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                    <i class="fa-solid fa-circle-plus"></i> <?php echo e(__('Nova Nota')); ?>

                </button>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10" x-data="notesApp()">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <?php if($notes->isEmpty()): ?>
                <div class="bg-white rounded-2xl shadow-card border border-brand-50 py-16 px-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-500">
                        <i class="fa-solid fa-note-sticky text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950"><?php echo e(__('Nenhuma nota ainda')); ?></h4>
                    <p class="mt-1 text-sm text-gray-500"><?php echo e(__('Crie sua primeira anotação rápida.')); ?></p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden flex flex-col" x-data="{ id: <?php echo e($note->id); ?> }">
                            <div class="flex items-center justify-between px-4 pt-4">
                                <input type="text" x-ref="title_<?php echo e($note->id); ?>" value="<?php echo e($note->title); ?>"
                                    @input.debounce.700ms="save(<?php echo e($note->id); ?>, $refs['title_<?php echo e($note->id); ?>'].value, $refs['content_<?php echo e($note->id); ?>'].value)"
                                    class="font-bold text-brand-950 text-sm border-none focus:ring-0 p-0 w-full bg-transparent" />

                                <form method="POST" action="<?php echo e(route('notes.destroy', $note->id)); ?>"
                                    onsubmit="return confirm('<?php echo e(__('Excluir esta nota?')); ?>');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-gray-300 hover:text-rose-500 shrink-0 ml-2"><i class="fa-solid fa-trash text-xs"></i></button>
                                </form>
                            </div>

                            <textarea x-ref="content_<?php echo e($note->id); ?>" rows="6"
                                @input.debounce.700ms="save(<?php echo e($note->id); ?>, $refs['title_<?php echo e($note->id); ?>'].value, $refs['content_<?php echo e($note->id); ?>'].value)"
                                placeholder="<?php echo e(__('Escreva aqui...')); ?>"
                                class="flex-1 border-none focus:ring-0 text-sm text-gray-700 p-4 resize-none bg-transparent"><?php echo e($note->content); ?></textarea>

                            <div class="px-4 pb-3 flex items-center justify-between text-[11px] text-gray-400">
                                <span x-text="status[id] ?? '<?php echo e(__('Atualizada')); ?> <?php echo e($note->updated_at->diffForHumans()); ?>'"></span>
                                <i class="fa-solid fa-circle-check text-emerald-400" x-show="status[id] === 'Salvo!'" x-cloak></i>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function notesApp() {
            return {
                status: {},
                save(id, title, content) {
                    this.status[id] = 'Salvando...';
                    fetch(`/notes/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ title, content }),
                    })
                    .then(r => r.json())
                    .then(() => { this.status[id] = 'Salvo!'; setTimeout(() => this.status[id] = null, 1500); })
                    .catch(() => { this.status[id] = 'Erro ao salvar'; });
                }
            }
        }
    </script>
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/notes/index.blade.php ENDPATH**/ ?>