@extends('layouts.app')

@section('content')
<div class="alert alert-danger">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Visite annulée</h5>
                                    <p class="mb-0"><strong>Raison :</strong> {{ $visit->cancellation_reason }}</p>
                                    <p class="mb-0 small">Annulée par : {{ $visit->cancelledBy->name }}</p>
                                </div>
                            </div>
                        </div>
@endsection