@extends('layouts.app')

@section('title', 'Calendrier')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: {!! json_encode($events) !!},
                eventClick: function(info) {
                    // Redirect to the event detail page
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        return false;
                    }
                },
                eventDidMount: function(info) {
                    // Add tooltips
                    $(info.el).tooltip({
                        title: info.event.extendedProps.description || '',
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                },
                dayMaxEvents: true, // allow "more" link when too many events
                selectable: true,
                select: function(info) {
                    // Handle date selection
                    const modal = document.getElementById('createEventModal');
                    document.getElementById('event_start_date').value = info.startStr;
                    document.getElementById('event_end_date').value = info.endStr;
                    
                    // Show modal
                    $(modal).modal('show');
                }
            });
            
            calendar.render();
        });
    </script>
@endpush

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Calendrier</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez vos rendez-vous et événements.</p>
            </div>
            <div>
                <button type="button" data-toggle="modal" data-target="#createEventModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvel événement
                </button>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="p-4">
                <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard"></div>
            </div>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Nouvel événement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="event_title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="event_title" name="title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="event_description" class="form-label">Description</label>
                            <textarea class="form-control" id="event_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_start_date" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="event_start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_start_time" class="form-label">Heure de début</label>
                                    <input type="time" class="form-control" id="event_start_time" name="start_time">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_end_date" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="event_end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="event_end_time" class="form-label">Heure de fin</label>
                                    <input type="time" class="form-control" id="event_end_time" name="end_time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="event_type" class="form-label">Type d'événement</label>
                            <select class="form-control" id="event_type" name="event_type">
                                <option value="appointment">Rendez-vous</option>
                                <option value="visit">Visite</option>
                                <option value="meeting">Réunion</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="event_color" class="form-label">Couleur</label>
                            <select class="form-control" id="event_color" name="color">
                                <option value="#3788d8">Bleu</option>
                                <option value="#e74c3c">Rouge</option>
                                <option value="#2ecc71">Vert</option>
                                <option value="#f39c12">Orange</option>
                                <option value="#9b59b6">Violet</option>
                                <option value="#1abc9c">Turquoise</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="event_all_day" name="all_day" value="1">
                                <label class="form-check-label" for="event_all_day">
                                    Toute la journée
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
