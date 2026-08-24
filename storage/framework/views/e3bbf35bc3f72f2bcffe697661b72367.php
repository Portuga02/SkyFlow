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
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                    <i class="fa-solid fa-circle-plus"></i> <?php echo e(__('Nova Nota')); ?>

                </button>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <?php if($notes->isEmpty()): ?>
            <div class="text-center py-12">
                <i class="fa-solid fa-note-sticky text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium"><?php echo e(__('Nenhuma nota ainda. Crie uma!')); ?></p>
            </div>
        <?php else: ?>
            <!-- Grid de Post-Its -->
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                <?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="break-inside-avoid rounded-lg shadow-card border border-brand-50 overflow-hidden hover:shadow-lg transition cursor-pointer group relative"
                        style="background-color: <?php echo e($note->color ?? '#fef08a'); ?>;"
                        @click="open<?php echo e($note->id); ?> = true" x-data="{ open<?php echo e($note->id); ?>: false }">

                        <!-- Card View (Closed) -->
                        <div class="p-4 min-h-[160px] flex flex-col pb-14 relative" x-show="!open<?php echo e($note->id); ?>">

                            <!-- Botão de Copiar Mágico -->
                            <button type="button"
                                @click.stop="navigator.clipboard.writeText($refs.noteContent.innerText); $el.innerHTML = '<i class=\'fa-solid fa-check\'></i>'; setTimeout(() => $el.innerHTML = '<i class=\'fa-regular fa-copy\'></i>', 2000)"
                                class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition text-gray-700 hover:text-brand-900 bg-black/5 hover:bg-black/10 rounded p-2 shadow-sm z-10"
                                title="Copiar texto">
                                <i class="fa-regular fa-copy"></i>
                            </button>

                            <h3 class="font-bold text-sm text-gray-900 line-clamp-2 mb-2">
                                <?php echo e($note->title ?: 'Sem título'); ?></h3>

                            <div x-ref="noteContent" class="text-xs text-gray-700 flex-1 prose-sm">
                                <?php echo $note->content ?: 'Clique para editar...'; ?>

                            </div>

                            <!-- BOTOES DE HOVER ORIGINAIS -->
                            <div
                                class="flex gap-2 opacity-0 group-hover:opacity-100 transition absolute bottom-4 left-4 right-4 z-10">
                                <button @click.stop="open<?php echo e($note->id); ?> = true"
                                    class="flex-1 py-1.5 text-xs font-semibold bg-black/10 hover:bg-black/20 rounded text-gray-900 transition">
                                    Editar
                                </button>
                                <form method="POST" action="<?php echo e(route('notes.destroy', $note->id)); ?>" class="flex-1"
                                    @click.stop>
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                        class="w-full py-1.5 text-xs font-semibold bg-red-500/30 hover:bg-red-500/50 rounded text-red-900 transition">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- O SEGREDO ESTÁ AQUI: x-teleport="body" tira o modal da prisão do post-it -->
                        <template x-teleport="body">
                            <!-- Edit Modal (Open) -->
                            <div x-show="open<?php echo e($note->id); ?>"
                                class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition
                                style="display: none;">

                                <!-- Fundo escuro (Backdrop) -->
                                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                                    @click="open<?php echo e($note->id); ?> = false"></div>

                                <!-- Janela do Modal -->
                                <div class="relative w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transition-colors duration-300"
                                    x-data="noteForm<?php echo e($note->id); ?>()" :style="`background-color: ${color}`">

                                    <!-- Header -->
                                    <div
                                        class="flex items-center justify-between p-4 border-b border-black/10 bg-black/5">
                                        <h3 class="font-bold text-gray-900"><?php echo e(__('Editar Nota')); ?></h3>
                                        <button @click="open<?php echo e($note->id); ?> = false"
                                            class="text-gray-600 hover:text-gray-900 transition">
                                            <i class="fa-solid fa-xmark text-xl"></i>
                                        </button>
                                    </div>

                                    <!-- Form -->
                                    <form method="POST" action="<?php echo e(route('notes.update', $note->id)); ?>"
                                        @submit.prevent="submit()" class="p-6 space-y-4">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Título')); ?></label>
                                            <!-- BG-WHITE FORÇADO PARA NÃO FICAR TRANSPARENTE -->
                                            <input type="text" name="title" x-model="title"
                                                class="w-full px-3 py-2 rounded-lg border border-black/10 focus:border-black/30 outline-none bg-white text-gray-900 transition shadow-sm">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Conteúdo')); ?></label>

                                            <div
                                                class="rounded-lg border border-black/10 focus-within:border-black/30 bg-white shadow-sm overflow-hidden transition">
                                                <!-- O Editor "Do Além" (Substitui a Textarea) -->
                                                <div class="w-full px-3 py-2 min-h-[150px] max-h-[300px] overflow-y-auto outline-none text-gray-900 prose-sm"
                                                    contenteditable="true" x-init="$el.innerHTML = content"
                                                    @input="content = $event.target.innerHTML">
                                                </div>

                                                <!-- A Barrinha Estilo Windows 7 Sticky Notes (Turbinada) -->
                                                <div
                                                    class="flex items-center gap-1 p-2 bg-black/5 border-t border-black/10 flex-wrap">
                                                    <!-- Negrito, Itálico, Sublinhado e Riscado -->
                                                    <button type="button"
                                                        @click="document.execCommand('bold', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 font-serif font-bold transition"
                                                        title="Negrito">B</button>
                                                    <button type="button"
                                                        @click="document.execCommand('italic', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 font-serif italic transition"
                                                        title="Itálico">I</button>
                                                    <button type="button"
                                                        @click="document.execCommand('underline', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 font-serif underline transition"
                                                        title="Sublinhado">U</button>
                                                    <button type="button"
                                                        @click="document.execCommand('strikeThrough', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 font-serif line-through transition"
                                                        title="Tachado">S</button>

                                                    <div class="w-px h-5 bg-black/20 mx-1"></div> <!-- Divisória -->

                                                    <!-- Listas -->
                                                    <button type="button"
                                                        @click="document.execCommand('insertUnorderedList', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 transition"
                                                        title="Lista de Pontos">
                                                        <i class="fa-solid fa-list-ul"></i>
                                                    </button>
                                                    <button type="button"
                                                        @click="document.execCommand('insertOrderedList', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 transition"
                                                        title="Lista Numerada">
                                                        <i class="fa-solid fa-list-ol"></i>
                                                    </button>

                                                    <div class="w-px h-5 bg-black/20 mx-1"></div> <!-- Divisória -->

                                                    <!-- Alinhamentos -->
                                                    <button type="button"
                                                        @click="document.execCommand('justifyLeft', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 transition"
                                                        title="Alinhar à Esquerda">
                                                        <i class="fa-solid fa-align-left"></i>
                                                    </button>
                                                    <button type="button"
                                                        @click="document.execCommand('justifyCenter', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 transition"
                                                        title="Centralizar">
                                                        <i class="fa-solid fa-align-center"></i>
                                                    </button>
                                                    <button type="button"
                                                        @click="document.execCommand('justifyRight', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-black/10 text-brand-950 transition"
                                                        title="Alinhar à Direita">
                                                        <i class="fa-solid fa-align-right"></i>
                                                    </button>

                                                    <div class="w-px h-5 bg-black/20 mx-1"></div> <!-- Divisória -->

                                                    <!-- Limpar Formatação -->
                                                    <button type="button"
                                                        @click="document.execCommand('removeFormat', false, null)"
                                                        class="w-8 h-8 flex items-center justify-center rounded hover:bg-rose-100 text-rose-600 transition"
                                                        title="Limpar Formatação">
                                                        <i class="fa-solid fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Input oculto para enviar o HTML pro Laravel -->
                                            <input type="hidden" name="content" x-model="content">
                                        </div>

                                        <!-- Color Picker com as 11 cores -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-900 mb-2"><?php echo e(__('Cor do Post-It')); ?></label>
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" @click="color = '#fef08a'"
                                                    class="h-8 w-8 rounded-lg bg-[#fef08a] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Amarelo"></button>
                                                <button type="button" @click="color = '#fecaca'"
                                                    class="h-8 w-8 rounded-lg bg-[#fecaca] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Vermelho"></button>
                                                <button type="button" @click="color = '#bfdbfe'"
                                                    class="h-8 w-8 rounded-lg bg-[#bfdbfe] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Azul"></button>
                                                <button type="button" @click="color = '#bbf7d0'"
                                                    class="h-8 w-8 rounded-lg bg-[#bbf7d0] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Verde"></button>
                                                <button type="button" @click="color = '#e9d5ff'"
                                                    class="h-8 w-8 rounded-lg bg-[#e9d5ff] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Roxo"></button>
                                                <button type="button" @click="color = '#f5d5e8'"
                                                    class="h-8 w-8 rounded-lg bg-[#f5d5e8] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Rosa"></button>

                                                <button type="button" @click="color = '#fed7aa'"
                                                    class="h-8 w-8 rounded-lg bg-[#fed7aa] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Laranja"></button>
                                                <button type="button" @click="color = '#99f6e4'"
                                                    class="h-8 w-8 rounded-lg bg-[#99f6e4] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Ciano"></button>
                                                <button type="button" @click="color = '#d9f99d'"
                                                    class="h-8 w-8 rounded-lg bg-[#d9f99d] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Limão"></button>
                                                <button type="button" @click="color = '#c7d2fe'"
                                                    class="h-8 w-8 rounded-lg bg-[#c7d2fe] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Índigo"></button>
                                                <button type="button" @click="color = '#e2e8f0'"
                                                    class="h-8 w-8 rounded-lg bg-[#e2e8f0] border border-black/20 hover:border-black shadow-sm transition"
                                                    title="Cinza"></button>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-3 justify-end pt-4 border-t border-black/10">
                                            <button type="button" @click="open<?php echo e($note->id); ?> = false"
                                                class="px-4 py-2 rounded-lg font-semibold text-gray-900 bg-black/10 hover:bg-black/20 transition">
                                                <?php echo e(__('Cancelar')); ?>

                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition shadow-md">
                                                <?php echo e(__('Salvar')); ?>

                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
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
                    content: <?php echo json_encode($note->content ?? ''); ?>,
                    color: '<?php echo e($note->color ?? '#fef08a'); ?>',
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
    <style>
        /* Força as listas a aparecerem dentro do nosso editor de notas */
        .prose-sm ul {
            list-style-type: disc !important;
            padding-left: 1.5rem !important;
            margin: 0.5rem 0 !important;
        }

        .prose-sm ol {
            list-style-type: decimal !important;
            padding-left: 1.5rem !important;
            margin: 0.5rem 0 !important;
        }

        .prose-sm li {
            display: list-item !important;
        }
    </style>
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