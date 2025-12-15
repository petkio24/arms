@extends('layouts.app')

@section('title', 'Рабочие станции')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Рабочие станции</h1>
        <a href="{{ route('workstations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Добавить
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Инв. номер</th>
                        <th>Название</th>
                        <th>Расположение</th>
                        <th>Статус</th>
                        <th>Компонентов</th>
                        <th>Создано</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workstations as $workstation)
                        <tr>
                            <td>
                                <strong>{{ $workstation->inventory_number }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('workstations.show', $workstation) }}" class="text-decoration-none">
                                    {{ $workstation->name }}
                                </a>
                            </td>
                            <td>{{ $workstation->location ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $workstation->status == 'active' ? 'success' :
                                    ($workstation->status == 'maintenance' ? 'warning' : 'secondary')
                                }}">
                                    {{ $statuses[$workstation->status] ?? $workstation->status }}
                                </span>
                            </td>
                            <td>{{ $workstation->current_components_count ?? 0 }}</td>
                            <td>{{ $workstation->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('workstations.show', $workstation) }}"
                                       class="btn btn-outline-info" title="Просмотр">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('workstations.edit', $workstation) }}"
                                       class="btn btn-outline-primary" title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('workstations.compare', $workstation) }}"
                                       class="btn btn-outline-warning" title="Сравнить конфигурации">
                                        <i class="bi bi-arrows-angle-contract"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $workstations->links() }}
            </div>
        </div>
    </div>
@endsection
