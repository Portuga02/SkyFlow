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
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight"><?php echo e(__('Calendário')); ?></h2>
            <p class="text-sm text-brand-600 mt-1"><?php echo e(__('Visualize e arraste suas tarefas entre datas.')); ?></p>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6 relative overflow-hidden">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- OPA! AQUI COMEÇA A MÁGICA DA GAVETA LATERAL (SIDEBAR) -->
    
    <!-- Fundo escuro (Backdrop) -->
    <div id="drawer-backdrop" class="fixed inset-0 bg-brand-950/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300" onclick="closeDrawer()"></div>

    <!-- Gaveta -->
    <div id="task-drawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-[400px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col border-l border-brand-100">
        
        <!-- Cabeçalho da Gaveta -->
        <div class="p-6 border-b border-brand-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-extrabold text-lg text-brand-950"><?php echo e(__('Detalhes da Tarefa')); ?></h3>
            <button onclick="closeDrawer()" class="text-gray-400 hover:text-rose-500 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Corpo da Gaveta -->
        <div class="p-6 flex-1 overflow-y-auto space-y-6">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"><?php echo e(__('Título')); ?></p>
                <h4 id="drawer-title" class="text-xl font-bold text-brand-900 leading-tight">--</h4>
            </div>

            <div class="flex items-center gap-3" id="drawer-badges">
                <!-- Badges de prioridade e categoria serão injetados aqui via JS -->
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2"><?php echo e(__('Descrição')); ?></p>
                <div class="bg-slate-50 rounded-xl p-4 border border-brand-50">
                    <p id="drawer-desc" class="text-sm text-gray-700 whitespace-pre-line">--</p>
                </div>
            </div>
        </div>

        <!-- Rodapé da Gaveta -->
        <div class="p-6 border-t border-brand-100 bg-white">
            <a id="drawer-link" href="#" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-md transition hover:-translate-y-0.5">
                <?php echo e(__('Acessar Tarefa Completa')); ?> <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </div>

    <!-- DEPENDÊNCIAS DO FULLCALENDAR -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.js"></script>
    
    <script>
        // Funções da Gaveta
        function openDrawer(event) {
            document.getElementById('drawer-title').innerText = event.title;
            document.getElementById('drawer-desc').innerText = event.extendedProps.description || 'Nenhuma descrição detalhada.';
            
            // Renderiza as tags de Prioridade e Categoria
            let badgesHtml = '';
            
            if (event.extendedProps.category) {
                badgesHtml += `<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-tag mr-1"></i> ${event.extendedProps.category}</span>`;
            }

            const prio = event.extendedProps.priority;
            if (prio === 'highest' || prio === 'high') {
                badgesHtml += `<span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-arrow-up mr-1"></i> Alta</span>`;
            } else if (prio === 'medium') {
                badgesHtml += `<span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-minus mr-1"></i> Média</span>`;
            } else if (prio === 'low' || prio === 'lowest') {
                badgesHtml += `<span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-arrow-down mr-1"></i> Baixa</span>`;
            }

            if (event.extendedProps.completed) {
                badgesHtml += `<span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-check mr-1"></i> Concluído</span>`;
            }

            document.getElementById('drawer-badges').innerHTML = badgesHtml;
            document.getElementById('drawer-link').href = `/todos/${event.id}`;

            // Mostra a gaveta com animação
            document.getElementById('drawer-backdrop').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('task-drawer').classList.remove('translate-x-full');
            }, 10);
        }

        function closeDrawer() {
            document.getElementById('task-drawer').classList.add('translate-x-full');
            setTimeout(() => {
                document.getElementById('drawer-backdrop').classList.add('hidden');
            }, 300);
        }

        // Setup do FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                height: 700, // Dá uma altura fixa para evitar que o calendário esprema
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Adicionei as visões de semana e dia!
                },
                editable: true,
                eventSources: [
                    {
                        url: '/calendar/events',
                        failure: () => console.error('Erro ao carregar eventos'),
                    }
                ],
                // Alterado: Agora abre a Gaveta em vez de redirecionar!
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); 
                    openDrawer(info.event);
                },
                eventDrop: function(info) {
                    const newDate = info.event.startStr;
                    fetch('/calendar/reschedule', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            id: info.event.id,
                            date: newDate,
                        }),
                    })
                    .then(r => r.json())
                    .then(() => {
                        console.log('Tarefa atualizada no banco com sucesso!');
                    })
                    .catch(() => {
                        info.revert();
                        alert('Erro ao atualizar a data. A tarefa voltará ao dia original.');
                    });
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps.completed) {
                        info.el.style.opacity = '0.5';
                        info.el.style.textDecoration = 'line-through';
                    }
                }
            });

            calendar.render();
        });
    </script>

    <style>
        /* Ajustes Premium para o Calendário */
        .fc {
            font-family: inherit;
        }
        .fc .fc-button-primary {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
            text-transform: capitalize;
            font-weight: 600;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        .fc .fc-button-primary:hover {
            background-color: #f1f5f9 !important;
        }
        .fc-theme-standard .fc-col-header-cell {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            padding: 12px 0;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #64748b;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #e2e8f0;
        }
        .fc-theme-standard .fc-daygrid-day:hover {
            background-color: #f8fafc;
        }
        .fc-event {
            border-radius: 6px !important;
            padding: 2px 4px !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
            border: none !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        /* Fix para o z-index do calendário não sobrepor a gaveta */
        .fc-header-toolbar {
            position: relative;
            z-index: 10;
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
<?php endif; ?><?php /**PATH C:\Workspace\SkyFlow\resources\views\calendar\index.blade.php ENDPATH**/ ?>