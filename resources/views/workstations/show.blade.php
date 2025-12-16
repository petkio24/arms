@extends('layouts.app')

@section('title', $workstation->name)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Информация о рабочей станции</h5>
                        <div class="btn-group">
                            <a href="{{ route('workstations.edit', $workstation) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Редактировать
                            </a>
                            <a href="{{ route('workstations.compare', $workstation) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-arrows-angle-contract"></i> Сравнить
                            </a>
                            <a href="{{ route('workstations.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Название:</th>
                                    <td>{{ $workstation->name }}</td>
                                </tr>
                                <tr>
                                    <th>Инв. номер:</th>
                                    <td><strong>{{ $workstation->inventory_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Расположение:</th>
                                    <td>
                                        @if($workstation->location)
                                            <a href="{{ route('locations.show', $workstation->location) }}">
                                                {{ $workstation->location->name }}
                                                @if($workstation->location->room)
                                                    ({{ $workstation->location->room }})
                                                @endif
                                            </a>
                                        @else
                                            <span class="text-muted">Не указано</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Статус:</th>
                                    <td>
                                    <span class="badge bg-{{
    $workstation->status == 'active' ? 'success' :
    ($workstation->status == 'maintenance' ? 'warning' : 'secondary')
}}">
    {{ $workstation->status == 'active' ? 'Активна' :
      ($workstation->status == 'maintenance' ? 'На обслуживании' :
      ($workstation->status == 'decommissioned' ? 'Списана' : $workstation->status)) }}
</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Дата создания:</th>
                                    <td>{{ $workstation->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Последнее обновление:</th>
                                    <td>{{ $workstation->updated_at->format('d.m.Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Компонентов установлено:</th>
                                    <td>{{ $workstation->currentComponents->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Записей в истории:</th>
                                    <td>{{ $workstation->configHistory->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($workstation->notes)
                        <div class="mt-3">
                            <h6>Примечания:</h6>
                            <p class="mb-0">{{ $workstation->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Быстрые действия</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('workstations.history', $workstation) }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history"></i> История изменений
                        </a>
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#installModal">
                            <i class="bi bi-plus-circle"></i> Добавить компонент
                        </button>
                        @if($workstation->status == 'active')
                            <form action="{{ route('workstations.change-status', $workstation) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <input type="hidden" name="status" value="maintenance">
                                <button type="submit" class="btn btn-outline-warning w-100 mb-2">
                                    <i class="bi bi-tools"></i> Перевести на обслуживание
                                </button>
                            </form>
                        @elseif($workstation->status == 'maintenance')
                            <form action="{{ route('workstations.change-status', $workstation) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn btn-outline-success w-100 mb-2">
                                    <i class="bi bi-check-circle"></i> Активировать
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Статистика по компонентам</h6>
                </div>
                <div class="card-body">
                    @php
                        $typeStats = $workstation->currentComponents->groupBy('type')->map->count();
                    @endphp
                    @if($typeStats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($typeStats as $type => $count)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $componentTypes[$type] ?? $type }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Компоненты не установлены</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($workstation->currentComponents->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Текущая конфигурация</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($workstation->currentComponents->groupBy('type') as $type => $components)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">{{ $componentTypes[$type] ?? $type }}</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($components as $component)
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ $component->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $component->model }}<br>
                                                            Инв. №: {{ $component->inventory_number }}
                                                        </small>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('components.show', $component) }}"
                                                           class="btn btn-outline-info btn-sm">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    Установлен: {{ \Carbon\Carbon::parse($component->pivot->installed_at)->format('d.m.Y') }}
                                                </small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($workstation->configHistory->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Последние изменения</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($workstation->configHistory->take(10) as $history)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $history->change_description }}</h6>
                                <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">
                                <span class="badge bg-secondary">{{ $history->change_type }}</span>
                            </p>
                            <small class="text-muted">Пользователь: {{ $history->user->name }}</small>
                        </div>
                    @endforeach
                </div>
                @if($workstation->configHistory->count() > 10)
                    <div class="card-footer text-center">
                        <a href="{{ route('workstations.history', $workstation) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-clock-history"></i> Вся история
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Modal для установки компонента -->
    <div class="modal fade" id="installModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Установка компонента</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($availableComponents->count() > 0)
                        <div class="list-group">
                            @foreach($availableComponents as $component)
                                <form action="{{ route('components.install', $component) }}" method="POST"
                                      class="list-group-item">
                                    @csrf
                                    <input type="hidden" name="workstation_id" value="{{ $workstation->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $component->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $component->model }} | {{ $component->inventory_number }}
                                            </small>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-cpu"></i> Установить
                                        </button>
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Нет доступных компонентов на складе</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
