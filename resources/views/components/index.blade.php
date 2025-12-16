@extends('layouts.app')

@section('title', 'Комплектующие')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Комплектующие</h1>
        <a href="{{ route('components.create') }}" class="btn btn-primary">
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
                        <th>Наименование</th>
                        <th>Тип</th>
                        <th>Производитель</th>
                        <th>Статус</th>
                        <th>Текущее место</th>
                        <th>Дата покупки</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td>
                                <strong>{{ $component->inventory_number }}</strong>
                                <br>
                                <small class="text-muted">{{ $component->serial_number }}</small>
                            </td>
                            <td>
                                {{ $component->name }}
                                @if($component->model)
                                    <br>
                                    <small class="text-muted">{{ $component->model }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $types[$component->type] ?? $component->type }}
                                </span>
                            </td>
                            <td>{{ $component->manufacturer ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $component->status == 'in_stock' ? 'success' :
                                    ($component->status == 'installed' ? 'primary' :
                                    ($component->status == 'defective' ? 'danger' : 'secondary'))
                                }}">
                                    {{ $statuses[$component->status] ?? $component->status }}
                                </span>
                            </td>
                            <td>
                                @if($component->current_workstation && $component->current_workstation->location)
                                    <a href="{{ route('locations.show', $component->current_workstation->location) }}">
                                        {{ $component->current_workstation->location->name }}
                                        @if($component->current_workstation->location->room)
                                            ({{ $component->current_workstation->location->room }})
                                        @endif
                                    </a>
                                @elseif($component->current_workstation)
                                    <a href="{{ route('workstations.show', $component->current_workstation) }}">
                                        {{ $component->current_workstation->name }}
                                    </a>
                                @else
                                    <span class="text-muted">На складе</span>
                                @endif
                            </td>
                            <td>{{ $component->purchase_date->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('components.show', $component) }}"
                                       class="btn btn-outline-info" title="Просмотр">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('components.edit', $component) }}"
                                       class="btn btn-outline-primary" title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('components.destroy', $component) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                title="Удалить"
                                                onclick="return confirm('Удалить комплектующее?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $components->links() }}
            </div>
        </div>
    </div>
@endsection
