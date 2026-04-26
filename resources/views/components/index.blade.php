@extends('layouts.app')

@section('title', 'Комплектующие')

@section('content')
    <!-- ФОРМА ФИЛЬТРАЦИИ -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('components.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Поиск</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Название, инв. номер..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Тип</label>
                        <select name="type" class="form-select">
                            <option value="">Все типы</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Статус</label>
                        <select name="status" class="form-select">
                            <option value="">Все статусы</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">На странице</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="row mt-3">
                    <div class="col-12">
                        @if(request('search') || request('type') || request('status'))
                            <a href="{{ route('components.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Сбросить фильтры
                            </a>
                        @endif

                        @if(request('search') || request('type') || request('status'))
                            <span class="text-muted ms-2 small">
                            <i class="bi bi-filter"></i> Фильтры активны
                        </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Комплектующие</h1>
        <div>
            <a href="{{ route('components.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>
                            <a href="{{ route('components.index', array_merge(request()->all(), ['sort_by' => 'inventory_number', 'sort_order' => (request('sort_by') == 'inventory_number' && request('sort_order') == 'asc') ? 'desc' : 'asc'])) }}"
                               class="text-decoration-none text-dark">
                                Инв. номер
                                @if(request('sort_by') == 'inventory_number')
                                    <i class="bi bi-caret-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-fill"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('components.index', array_merge(request()->all(), ['sort_by' => 'name', 'sort_order' => (request('sort_by') == 'name' && request('sort_order') == 'asc') ? 'desc' : 'asc'])) }}"
                               class="text-decoration-none text-dark">
                                Наименование
                                @if(request('sort_by') == 'name')
                                    <i class="bi bi-caret-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-fill"></i>
                                @endif
                            </a>
                        </th>
                        <th>Тип</th>
                        <th>Производитель</th>
                        <th>Статус</th>
                        <th>Текущее место</th>
                        <th>Дата покупки</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($components as $component)
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
                                @if($component->socket)
                                    <br>
                                    <span class="badge bg-secondary">{{ $component->socket }}</span>
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
                                @if($component->current_workstation)
                                    <a href="{{ route('workstations.show', $component->current_workstation) }}" class="text-decoration-none">
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2">Комплектующие не найдены</p>
                                <a href="{{ route('components.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Добавить
                                </a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    Показано {{ $components->firstItem() ?? 0 }} - {{ $components->lastItem() ?? 0 }} из {{ $components->total() }}
                </div>
                <div>
                    {{ $components->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
        }
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            border-radius: 6px;
        }
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            .btn-group-sm > .btn {
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
@endsection
