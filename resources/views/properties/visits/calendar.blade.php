@extends('components.back.layout.back')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Calendrier des Visites</h1>
        <div class="flex space-x-2">
            <a href="{{ route('visits.index') }}" class="btn btn-secondary">
                <i class="fas fa-list mr-2"></i> Vue Liste
            </a>
            @can('create', App\Models\PropertyVisit::class)
                <a href="{{ route('visits.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Nouvelle Visite
                </a>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [
                FullCalendar.dayGridPlugin,
                FullCalendar.timeGridPlugin,
                FullCalendar.interactionPlugin,
                FullCalendar.listPlugin
            ],
            locale: 'fr',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            events: {
                url: "{{ route('visits.calendar.events') }}",
                method: 'GET',
                failure: function() {
                    console.error('Erreur de chargement des visites');
                }
            },
            eventContent: function(arg) {
            // Personnalisation du rendu pour utiliser les classes Tailwind
            return {
                html: `
                    <div class="p-1 ${arg.event.backgroundColor} ${arg.event.textColor} 
                                rounded ${arg.event.borderColor} border">
                        <strong>${arg.event.title}</strong>
                        <div class="text-xs">${arg.event.extendedProps.status}</div>
                    </div>
                `
            };
        },
            eventClick: function(info) {
                window.location.href = "{{ route('visits.show', '') }}/" + info.event.id;
            },
            selectable: @json(auth()->user()->can('create', App\Models\PropertyVisit::class)),
            select: function(info) {
                @if(auth()->user()->isClient() || auth()->user()->isIndividual())
                    showVisitTypeChoiceModal(info.startStr, info.endStr);
                @else
                    window.location.href = "{{ route('visits.create') }}?date=" + info.startStr;
                @endif
            }
        });
        
        calendar.render();

        function showVisitTypeChoiceModal(start, end) {
            Swal.fire({
                title: 'Type de visite',
                text: 'Choisissez le type de visite à créer',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Visite privée',
                cancelButtonText: 'Visite de propriété',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('visits.create') }}?private=true&date=" + start;
                } else {
                    window.location.href = "{{ route('visits.create') }}?date=" + start;
                }
            });
        }
    } else {
        console.error("Element #calendar non trouvé");
    }
});
</script>
@endpush