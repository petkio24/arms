@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Всего станций</h6>
                            <h2 class="mb-0">{{ $stats['total_workstations'] }}</h2>
                        </div>
                        <i class="bi bi-pc-display display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Активные</h6>
                            <h2 class="mb-0">{{ $stats['active_workstations'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Всего компонентов</h6>
                            <h2 class="mb-0">{{ $stats['total_components'] }}</h2>
                        </div>
                        <i class="bi bi-motherboard display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">На складе</h6>
                            <h2 class="mb-0">{{ $stats['in_stock_components'] }}</h2>
                        </div>
                        <i class="bi bi-box-seam display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Последние изменения</h5>
                </div>
                <div class="card-body">
                    @if($recentChanges->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentChanges as $change)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $change->change_description }}</h6>
                                        <small class="text-muted">{{ $change->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <span class="badge bg-secondary">{{ $change->change_type }}</span>
                                        <small class="text-muted">
                                            Станция: {{ $change->workstation->name }}
                                        </small>
                                    </p>
                                    <small class="text-muted">Пользователь: {{ $change->user->name }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Изменений пока нет</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Последние станции</h5>
                </div>
                <div class="card-body">
                    @if($workstations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($workstations as $workstation)
                                <a href="{{ route('workstations.show', $workstation) }}"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $workstation->name }}</h6>
                                        <span class="badge bg-{{ $workstation->status == 'active' ? 'success' : 'warning' }}">
                                        {{ $workstation->status }}
                                    </span>
                                    </div>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i>
                                            @if($workstation->location)
                                                {{ $workstation->location->name }}
                                                @if($workstation->location->room)
                                                    ({{ $workstation->location->room }})
                                                @endif
                                            @else
                                                Не указано
                                            @endif
                                        </small>
                                    </p>
                                    <small class="text-muted">
                                        Компонентов: {{ $workstation->current_components_count }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Станций пока нет</p>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('workstations.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Добавить станцию
                    </a>
                    <a href="{{ route('components.create') }}" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="bi bi-plus-circle"></i> Добавить компонент
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
