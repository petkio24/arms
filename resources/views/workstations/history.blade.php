@extends('layouts.app')

@section('title', 'История изменений - ' . $workstation->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-clock-history"></i>
            История изменений: {{ $workstation->name }}
        </h1>
        <div>
            <a href="{{ route('workstations.show', $workstation) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад к станции
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Записи истории конфигураций</h5>
        </div>
        <div class="card-body">
            @if($history->count() > 0)
                <div class="timeline">
                    @foreach($history as $record)
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker bg-{{
                            $record->change_type == 'assembly' ? 'primary' :
                            ($record->change_type == 'upgrade' ? 'success' :
                            ($record->change_type == 'repair' ? 'warning' : 'info'))
                        }}"></div>
                            <div class="timeline-content">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $record->change_description }}</h6>
                                                <p class="mb-1">
                                                <span class="badge bg-secondary">
                                                    {{ App\Models\ConfigHistory::getChangeTypes()[$record->change_type] ?? $record->change_type }}
                                                </span>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $record->user->name }}
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                {{ $record->created_at->format('d.m.Y H:i') }}
                                            </small>
                                        </div>

                                        @if($record->components_before || $record->components_after)
                                            <div class="row mt-3">
                                                @if($record->components_before)
                                                    <div class="col-md-6">
                                                        <small class="text-muted">До изменения:</small>
                                                        <div class="mt-1">
                                                            @foreach($record->components_before as $type => $components)
                                                                @foreach($components as $component)
                                                                    <div class="badge bg-light text-dark me-1 mb-1">
                                                                        {{ $component['name'] }}
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($record->components_after)
                                                    <div class="col-md-6">
                                                        <small class="text-muted">После изменения:</small>
                                                        <div class="mt-1">
                                                            @foreach($record->components_after as $type => $components)
                                                                @foreach($components as $component)
                                                                    <div class="badge bg-light text-dark me-1 mb-1">
                                                                        {{ $component['name'] }}
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $history->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history display-4 text-muted mb-3"></i>
                    <p class="text-muted">История изменений отсутствует</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
        }
        .timeline-marker {
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid white;
        }
        .timeline-content {
            margin-left: 1rem;
        }
    </style>
@endsection
