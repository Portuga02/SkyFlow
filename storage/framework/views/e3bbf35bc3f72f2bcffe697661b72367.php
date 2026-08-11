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
                <h2 class="font-extrabold text-2xl text-brand-950"><?php echo e(__('Bloco de Notas')); ?></h2>
                <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Suas anotações rápidas e coloridas.')); ?></p>
            </div>
            <form method="POST" action="<?php echo e(route('notes.store')); ?>" class="flex-shrink-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                    <i class="fa-solid fa-circle-plus"></i> <?php echo e(__('Nova Nota')); ?>

                </button>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto">
        <?php if($notes->isEmpty()): ?>
            <div class="text-center py-12">
                <i class="fa-solid fa-note-sticky text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium"><?php echo e(__('Nenhuma nota ainda. Crie uma!')); ?></p>
            </div>
        <?php else: ?>
            <!-- Grid de Post-Its -->
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="break-inside-avoid bg-white rounded-lg shadow-card border border-brand-50 overflow-hidden hover:shadow-lg transition cursor-pointer group"
                        style="background-color: <?php echo e($note->color); ?>; opacity: 0.95;"
                        @click="open<?php echo e($note->id); ?> = true"
                        x-data="{ open<?php echo e($note->id); ?>: false }">

                        <!-- Card View (Closed) -->
                        <div class="p-4 min-h-32 flex flex-col" x-show="!open<?php echo e($note->id); ?>">
                            <h3 class="font-bold text-sm text-gray-900 line-clamp-2 mb-2"><?php echo e($note->title ?: 'Sem título'); ?></h3>
                            <p class="text-xs text-gray-700 line-clamp-4 flex-1"><?php echo e($note->content ?: 'Clique para editar...'); ?></p>
                            <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition">
                                <button @click.stop="open<?php echo e($note->id); ?> = true"
                                    class="flex-1 py-1.5 text-xs font-semibold bg-black/10 hover:bg-black/20 rounded text-gray-900 transition">
                                    Editar
                                </button>
                                <form method="POST" action="<?php echo e(route('notes.destroy', $note->id)); ?>" class="flex-1" @click.stop>
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-full py-1.5 text-xs font-semibold bg-red-500/20 hover:bg-red-500/30 rounded text-red-900 transition">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal (Open) -->
                        <div x-show="open<?php echo e($note->id); ?>" @click.stop @click.outside="open<?php echo e($note->id); ?> = false"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-transition>

                            <div class="w-full max-w-2xl rounded-xl shadow-lg overflow-hidden" @click.stop
                                :style="`background-color: <?php echo e($note->color); ?>`">

                                <!-- Header -->
                                <div class="flex items-center justify-between p-4 border-b border-black/10 bg-black/5">
                                    <h3 class="font-bold text-gray-900"><?php echo e(__('Editar Nota')); ?></h3>
                                    <button @click="open<?php echo e($note->id); ?> = false" class="text-gray-600 hover:text-gray-900">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <!-- Form -->
                                <form method="POST" action="<?php echo e(route('notes.update', $note->id)); ?>" x-data="noteForm<?php echo e($note->id); ?>()" @submit.prevent="submit()" class="p-6 space-y-4">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Título')); ?></label>
                                        <input type="text" name="title" x-model="title"
                                            class="w-full px-3 py-2 rounded-lg border border-black/10 focus:border-black/30 outline-none bg-white/90 text-gray-900">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Conteúdo')); ?></label>
                                        <textarea name="content" x-model="content" rows="6"
                                            class="w-full px-3 py-2 rounded-lg border border-black/10 focus:border-black/30 outline-none bg-white/90 text-gray-900 resize-none"></textarea>
                                    </div>

                                    <!-- Color Picker -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Cor do Post-It')); ?></label>
                                        <div class="flex items-center gap-3">
                                            <input type="color" name="color" x-model="color" class="h-10 w-16 rounded-lg cursor-pointer border border-black/10">
                                            <div class="flex gap-2">
                                                <button type="button" @click="color = '#fef08a'" class="h-8 w-8 rounded-lg bg-yellow-200 border-2 border-gray-300 hover:border-gray-900" title="Amarelo"></button>
                                                <button type="button" @click="color = '#fecaca'" class="h-8 w-8 rounded-lg bg-red-200 border-2 border-gray-300 hover:border-gray-900" title="Vermelho"></button>
                                                <button type="button" @click="color = '#bfdbfe'" class="h-8 w-8 rounded-lg bg-blue-200 border-2 border-gray-300 hover:border-gray-900" title="Azul"></button>
                                                <button type="button" @click="color = '#bbf7d0'" class="h-8 w-8 rounded-lg bg-green-200 border-2 border-gray-300 hover:border-gray-900" title="Verde"></button>
                                                <button type="button" @click="color = '#e9d5ff'" class="h-8 w-8 rounded-lg bg-purple-200 border-2 border-gray-300 hover:border-gray-900" title="Roxo"></button>
                                                <button type="button" @click="color = '#f5d5e8'" class="h-8 w-8 rounded-lg bg-pink-200 border-2 border-gray-300 hover:border-gray-900" title="Rosa"></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-3 justify-end pt-4 border-t border-black/10">
                                        <button type="button" @click="open<?php echo e($note->id); ?> = false"
                                            class="px-4 py-2 rounded-lg font-semibold text-gray-900 bg-black/10 hover:bg-black/20 transition">
                                            <?php echo e(__('Cancelar')); ?>

                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition">
                                            <?php echo e(__('Salvar')); ?>

                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            function noteForm<?php echo e($note->id); ?>() {
                return {
                    title: '<?php echo e($note->title); ?>',
                    content: '<?php echo e(addslashes($note->content)); ?>',
                    color: '<?php echo e($note->color); ?>',
                    async submit() {
                        try {
                            const res = await fetch('<?php echo e(route('notes.update', $note->id)); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-HTTP-Method-Override': 'PATCH'
                                },
                                body: JSON.stringify({
                                    title: this.title,
                                    content: this.content,
                                    color: this.color
                                })
                            });
                            if (res.ok) {
                                open<?php echo e($note->id); ?> = false;
                                location.reload();
                            }
                        } catch (err) {
                            alert('Erro ao salvar nota');
                        }
                    }
                }
            }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\Workspace\SkyFlow\resources\views/notes/index.blade.php ENDPATH**/ ?>