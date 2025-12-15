@extends('layouts.app')

@section('title', 'Сравнение конфигураций - ' . $workstation->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-arrows-angle-contract"></i>
            Сравнение конфигураций: {{ $workstation->name }}
        </h1>
        <a href="{{ route('workstations.show', $workstation) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Назад к станции
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Информация о станции</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Название:</strong> {{ $workstation->name }}<br>
                    <strong>Инв. номер:</strong> {{ $workstation->inventory_number }}
                </div>
                <div class="col-md-4">
                    <strong>Расположение:</strong> {{ $workstation->location ?? 'Не указано' }}<br>
                    <strong>Статус:</strong>
                    <span class="badge bg-{{ $workstation->status == 'active' ? 'success' : 'warning' }}">
{{ $workstation->status == 'active' ? 'Активна' :
   ($workstation->status == 'maintenance' ? 'На обслуживании' :
   ($workstation->status == 'decommissioned' ? 'Списана' : $workstation->status)) }}                </span>
                </div>
                <div class="col-md-4">
                    @if($workstation->initial_config)
                        <form action="{{ route('workstations.save-initial', $workstation) }}" method="POST">
                            @csrf
                            <input type="hidden" name="config" value="{{ json_encode($current) }}">
                            <button type="submit" class="btn btn-warning"
                                    onclick="return confirm('Текущая конфигурация будет сохранена как первоначальная. Продолжить?')">
                                <i class="bi bi-save"></i> Сохранить текущую как первоначальную
                            </button>
                        </form>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bi bi-info-circle"></i> Первоначальная конфигурация не сохранена
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header {{ $initial ? 'bg-primary text-white' : 'bg-light' }}">
                    <h5 class="mb-0">
                        Первоначальная конфигурация
                        @if(!$initial)
                            <span class="badge bg-secondary ms-2">Не задана</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($initial)
                        @foreach($initial as $type => $components)
                            <div class="mb-3">
                                <h6>{{ App\Models\Component::getTypes()[$type] ?? $type }}</h6>
                                <div class="list-group">
                                    @foreach($components as $component)
                                        <div class="list-group-item py-2">
                                            <strong>{{ $component['name'] }}</strong><br>
                                            <small class="text-muted">
                                                {{ $component['model'] ?? '' }}<br>
                                                Инв. №: {{ $component['inventory_number'] }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-info-circle display-4 text-muted mb-3"></i>
                            <p class="text-muted">Первоначальная конфигурация не сохранена</p>
                            <form action="{{ route('workstations.save-initial', $workstation) }}" method="POST">
                                @csrf
                                <input type="hidden" name="config" value="{{ json_encode($current) }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Сохранить текущую как первоначальную
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Текущая конфигурация</h5>
                </div>
                <div class="card-body">
                    @if($current)
                        @foreach($current as $type => $components)
                            <div class="mb-3">
                                <h6>{{ App\Models\Component::getTypes()[$type] ?? $type }}</h6>
                                <div class="list-group">
                                    @foreach($components as $component)
                                        @php
                                            $isChanged = true;
                                            if ($initial && isset($initial[$type])) {
                                                foreach ($initial[$type] as $initComponent) {
                                                    if ($initComponent['inventory_number'] == $component['inventory_number']) {
                                                        $isChanged = false;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="list-group-item py-2 {{ $isChanged ? 'border-start border-3 border-warning' : '' }}">
                                            @if($isChanged)
                                                <span class="badge bg-warning float-end">Изменено</span>
                                            @endif
                                            <strong>{{ $component['name'] }}</strong><br>
                                            <small class="text-muted">
                                                {{ $component['model'] ?? '' }}<br>
                                                Инв. №: {{ $component['inventory_number'] }}
                                            </small>
                                            @if($component['installed_at'])
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($component['installed_at'])->format('d.m.Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-cpu display-4 text-muted mb-3"></i>
                            <p class="text-muted">Компоненты не установлены</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Сводка изменений</h5>
        </div>
        <div class="card-body">
            @if($initial && $current)
                @php
                    $added = [];
                    $removed = [];

                    // Находим добавленные компоненты
                    foreach ($current as $type => $components) {
                        if (!isset($initial[$type])) {
                            $added = array_merge($added, $components);
                        } else {
                            foreach ($components as $component) {
                                $found = false;
                                foreach ($initial[$type] as $initComponent) {
                                    if ($initComponent['inventory_number'] == $component['inventory_number']) {
                                        $found = true;
                                        break;
                                    }
                                }
                                if (!$found) {
                                    $added[] = $component;
                                }
                            }
                        }
                    }

                    // Находим удаленные компоненты
                    foreach ($initial as $type => $components) {
                        if (!isset($current[$type])) {
                            $removed = array_merge($removed, $components);
                        } else {
                            foreach ($components as $component) {
                                $found = false;
                                foreach ($current[$type] as $currComponent) {
                                    if ($currComponent['inventory_number'] == $component['inventory_number']) {
                                        $found = true;
                                        break;
                                    }
                                }
                                if (!$found) {
                                    $removed[] = $component;
                                }
                            }
                        }
                    }
                @endphp

                <div class="row">
                    @if(count($added) > 0)
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <h6><i class="bi bi-plus-circle"></i> Добавлено: {{ count($added) }}</h6>
                                <ul class="mb-0">
                                    @foreach($added as $component)
                                        <li>
                                            {{ $component['name'] }}
                                            <small class="text-muted">({{ $component['inventory_number'] }})</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(count($removed) > 0)
                        <div class="col-md-6">
                            <div class="alert alert-danger">
                                <h6><i class="bi bi-dash-circle"></i> Удалено: {{ count($removed) }}</h6>
                                <ul class="mb-0">
                                    @foreach($removed as $component)
                                        <li>
                                            {{ $component['name'] }}
                                            <small class="text-muted">({{ $component['inventory_number'] }})</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                @if(count($added) == 0 && count($removed) == 0)
                    <div class="alert alert-info">
                        <i class="bi bi-check-circle"></i> Конфигурации идентичны
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    @if(!$initial)
                        Первоначальная конфигурация не сохранена
                    @else
                        Текущая конфигурация отсутствует
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
