@extends('layouts.app')

@section('title', $location->name)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Информация о помещении</h5>
                        <div class="btn-group">
                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Редактировать
                            </a>
                            <a href="{{ route('locations.index') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <td>{{ $location->name }}</td>
                                </tr>
                                <tr>
                                    <th>Адрес:</th>
                                    <td>{{ $location->full_address }}</td>
                                </tr>
                                @if($location->description)
                                    <tr>
                                        <th>Описание:</th>
                                        <td>{{ $location->description }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Ответственный:</th>
                                    <td>{{ $location->responsible_person ?? 'Не назначен' }}</td>
                                </tr>
                                <tr>
                                    <th>Телефон:</th>
                                    <td>{{ $location->phone ?? 'Не указан' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $location->email ?? 'Не указан' }}</td>
                                </tr>
                                <tr>
                                    <th>Рабочих станций:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $location->workstations_count }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Быстрые действия</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('workstations.create') }}?location_id={{ $location->id }}"
                           class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Добавить рабочую станцию
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($location->workstations->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Рабочие станции в этом помещении</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Инв. номер</th>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Компонентов</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($location->workstations as $workstation)
                            <tr>
                                <td>
                                    <strong>{{ $workstation->inventory_number }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('workstations.show', $workstation) }}" class="text-decoration-none">
                                        {{ $workstation->name }}
                                    </a>
                                </td>
                                <td>
                                <span class="badge bg-{{
                                    $workstation->status == 'active' ? 'success' :
                                    ($workstation->status == 'maintenance' ? 'warning' : 'secondary')
                                }}">
                                    {{ $workstation->status_text }}
                                </span>
                                </td>
                                <td>{{ $workstation->current_components_count ?? 0 }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('workstations.show', $workstation) }}"
                                           class="btn btn-outline-info" title="Просмотр">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('workstations.compare', $workstation) }}"
                                           class="btn btn-outline-warning" title="Сравнить">
                                            <i class="bi bi-arrows-angle-contract"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
