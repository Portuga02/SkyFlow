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
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                editable: true,
                eventSources: [
                    {
                        url: '/calendar/events',
                        failure: () => console.error('Erro ao carregar eventos'),
                    }
                ],
                eventClick: function(info) {
                    window.location.href = `/todos/${info.event.id}`;
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
                        console.log('Tarefa rescheduleada com sucesso');
                    })
                    .catch(() => {
                        info.revert();
                        alert('Erro ao atualizar a data');
                    });
                },
                eventDidMount: function(info) {
                    if (info.event.classNames.includes('completed')) {
                        info.el.style.opacity = '0.6';
                        info.el.style.textDecoration = 'line-through';
                    }
                }
            });

            calendar.render();
        });
    </script>

    <style>
        .fc .fc-button-primary {
            background-color: #0c8fe6 !important;
            border-color: #0c8fe6 !important;
        }
        .fc .fc-button-primary:hover {
            background-color: #0a7bc5 !important;
            border-color: #0a7bc5 !important;
        }
        .fc .fc-button-primary.fc-button-active {
            background-color: #0a6fa8 !important;
            border-color: #0a6fa8 !important;
        }
        .fc-theme-standard .fc-col-header-cell {
            background-color: #f0f7ff;
            border-color: #e0eaff;
        }
        .fc-theme-standard .fc-daygrid-day {
            border-color: #e0eaff;
        }
        .fc-theme-standard .fc-daygrid-day:hover {
            background-color: #f8fbff;
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
<?php /**PATH C:\Workspace\SkyFlow\resources\views/calendar/index.blade.php ENDPATH**/ ?>