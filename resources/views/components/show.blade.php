@extends('layouts.app')

@section('title', $component->name)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Информация о комплектующем</h5>
                        <div class="btn-group">
                            <a href="{{ route('components.edit', $component) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Редактировать
                            </a>
                            <a href="{{ route('components.index') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <th style="width: 40%">Наименование:</th>
                                    <td>{{ $component->name }}</td>
                                </tr>
                                <tr>
                                    <th>Тип:</th>
                                    <td>
                                    <span class="badge bg-info">
                                        {{ $types[$component->type] ?? $component->type }}
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Модель:</th>
                                    <td>{{ $component->model ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Производитель:</th>
                                    <td>{{ $component->manufacturer ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Инв. номер:</th>
                                    <td><strong>{{ $component->inventory_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Серийный номер:</th>
                                    <td>{{ $component->serial_number }}</td>
                                </tr>
                                <tr>
                                    <th>Статус:</th>
                                    <td>
                                    <span class="badge bg-{{
                                        $component->status == 'in_stock' ? 'success' :
                                        ($component->status == 'installed' ? 'primary' :
                                        ($component->status == 'defective' ? 'danger' : 'secondary'))
                                    }}">
                                        {{ $statuses[$component->status] ?? $component->status }}
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Дата покупки:</th>
                                    <td>{{ $component->purchase_date->format('d.m.Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($component->specifications)
                        <div class="mt-3">
                            <h6>Характеристики:</h6>
                            <p class="mb-0">{{ $component->specifications }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($component->status == 'in_stock')
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Установить в рабочую станцию</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('components.install', $component) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Выберите станцию:</label>
                                <select name="workstation_id" class="form-select" required>
                                    <option value="">Выберите станцию...</option>
                                    @foreach($workstations ?? [] as $workstation)
                                        <option value="{{ $workstation->id }}">
                                            {{ $workstation->name }} ({{ $workstation->inventory_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Примечание:</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <div id="compatibility_result" class="mb-3"></div>
                            <button type="submit" id="install_btn" class="btn btn-success w-100" disabled>
                                <i class="bi bi-cpu"></i> Установить
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($component->status == 'installed' && $component->current_workstation)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Текущее расположение</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Станция:</strong>
                            <a href="{{ route('workstations.show', $component->current_workstation) }}">
                                {{ $component->current_workstation->name }}
                            </a>
                        </p>
                        <form action="{{ route('components.remove', $component) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Причина удаления:</label>
                                <textarea name="notes" class="form-control" rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning w-100"
                                    onclick="return confirm('Удалить компонент со станции?')">
                                <i class="bi bi-x-circle"></i> Удалить со станции
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($component->workstations->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">История установок</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Рабочая станция</th>
                            <th>Дата установки</th>
                            <th>Дата удаления</th>
                            <th>Примечание</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($component->workstations as $workstation)
                            <tr>
                                <td>
                                    <a href="{{ route('workstations.show', $workstation) }}">
                                        {{ $workstation->name }}
                                    </a>
                                </td>
                                <td>{{ $workstation->pivot->installed_at ? \Carbon\Carbon::parse($workstation->pivot->installed_at)->format('d.m.Y') : '-' }}</td>
                                <td>
                                    @if($workstation->pivot->removed_at)
                                        {{ \Carbon\Carbon::parse($workstation->pivot->removed_at)->format('d.m.Y') }}
                                    @elseif(!$workstation->pivot->removed_at && $workstation->id == ($component->current_workstation->id ?? null))
                                        <span class="badge bg-success">Установлен</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $workstation->pivot->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.getElementById('workstation_select').addEventListener('change', function() {
                const workstationId = this.value;
                const componentId = {{ $component->id }};
                const resultDiv = document.getElementById('compatibility_result');
                const installBtn = document.getElementById('install_btn');

                if (!workstationId) {
                    resultDiv.innerHTML = '';
                    installBtn.disabled = true;
                    return;
                }

                // Показываем проверку
                resultDiv.innerHTML = '<div class="alert alert-info"><i class="bi bi-hourglass-split"></i> Проверка совместимости...</div>';
                installBtn.disabled = true;

                // AJAX запрос к серверу для проверки
                fetch('{{ route("components.check-compatibility", $component) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ workstation_id: workstationId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.compatible) {
                            resultDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ✅ Компонент совместим! Можно устанавливать.</div>';
                            installBtn.disabled = false;
                        } else {
                            let html = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ❌ Обнаружены проблемы:<br>';
                            data.errors.forEach(error => {
                                html += '• ' + error + '<br>';
                            });
                            html += '</div>';
                            resultDiv.innerHTML = html;
                            installBtn.disabled = true;
                        }
                    })
                    .catch(error => {
                        resultDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> ⚠️ Не удалось проверить совместимость</div>';
                        installBtn.disabled = false;
                    });
            });
            document.getElementById('workstation_select').addEventListener('change', function() {
                const workstationId = this.value;
                const componentId = {{ $component->id }};

                if (workstationId) {
                    // Показываем индикатор загрузки
                    const resultDiv = document.getElementById('compatibility_result');
                    resultDiv.innerHTML = '<div class="alert alert-info">Проверка совместимости...</div>';

                    // Отправляем AJAX запрос
                    fetch(`/components/${componentId}/check-compatibility`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ workstation_id: workstationId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.compatible) {
                                let html = '<div class="alert alert-success">✅ Компонент совместим';
                                if (data.warnings && data.warnings.length > 0) {
                                    html += '<br><small>' + data.warnings.join('<br>') + '</small>';
                                }
                                html += '</div>';
                                resultDiv.innerHTML = html;
                                document.getElementById('install_btn').disabled = false;
                            } else {
                                let html = '<div class="alert alert-danger">❌ Обнаружены проблемы:<br>';
                                html += data.errors.join('<br>');
                                html += '</div>';
                                resultDiv.innerHTML = html;
                                document.getElementById('install_btn').disabled = true;
                            }
                        })
                        .catch(error => {
                            resultDiv.innerHTML = '<div class="alert alert-warning">⚠️ Не удалось проверить совместимость</div>';
                        });
                }
            });
        </script>
    @endpush
@endsection
