@extends('layouts.app')

@section('title', 'Редактирование ' . $component->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Редактирование комплектующего</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('components.update', $component) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Наименование *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $component->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Тип *</label>
                                    <select class="form-select @error('type') is-invalid @enderror"
                                            id="type" name="type" required>
                                        <option value="">Выберите тип...</option>
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $component->type) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Модель</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                           id="model" name="model" value="{{ old('model', $component->model) }}">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Производитель</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                           id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $component->manufacturer) }}">
                                    @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Серийный номер *</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror"
                                           id="serial_number" name="serial_number" value="{{ old('serial_number', $component->serial_number) }}" required>
                                    @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inventory_number" class="form-label">Инвентарный номер *</label>
                                    <input type="text" class="form-control @error('inventory_number') is-invalid @enderror"
                                           id="inventory_number" name="inventory_number" value="{{ old('inventory_number', $component->inventory_number) }}" required>
                                    @error('inventory_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3 mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Технические характеристики</h6>
                            </div>
                            <div class="card-body">
                                <div id="tech-specs">
                                    <div class="spec-socket" style="display: none;">
                                        <div class="mb-3">
                                            <label for="socket" class="form-label">Сокет</label>
                                            <select class="form-select" id="socket" name="socket">
                                                <option value="">Не указан</option>
                                                <option value="LGA1700" {{ old('socket', $component->socket) == 'LGA1700' ? 'selected' : '' }}>LGA1700 (Intel 12-14 gen)</option>
                                                <option value="LGA1200" {{ old('socket', $component->socket) == 'LGA1200' ? 'selected' : '' }}>LGA1200 (Intel 10-11 gen)</option>
                                                <option value="AM5" {{ old('socket', $component->socket) == 'AM5' ? 'selected' : '' }}>AM5 (AMD Ryzen 7000+)</option>
                                                <option value="AM4" {{ old('socket', $component->socket) == 'AM4' ? 'selected' : '' }}>AM4 (AMD Ryzen 1000-5000)</option>
                                                <option value="LGA2066" {{ old('socket', $component->socket) == 'LGA2066' ? 'selected' : '' }}>LGA2066 (Intel Extreme)</option>
                                                <option value="TR4" {{ old('socket', $component->socket) == 'TR4' ? 'selected' : '' }}>TR4 (AMD Threadripper)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="spec-ram_type" style="display: none;">
                                        <div class="mb-3">
                                            <label for="ram_type" class="form-label">Тип оперативной памяти</label>
                                            <select class="form-select" id="ram_type" name="ram_type">
                                                <option value="">Не указан</option>
                                                <option value="DDR5" {{ old('ram_type', $component->ram_type) == 'DDR5' ? 'selected' : '' }}>DDR5</option>
                                                <option value="DDR4" {{ old('ram_type', $component->ram_type) == 'DDR4' ? 'selected' : '' }}>DDR4</option>
                                                <option value="DDR3" {{ old('ram_type', $component->ram_type) == 'DDR3' ? 'selected' : '' }}>DDR3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="spec-form_factor" style="display: none;">
                                        <div class="mb-3">
                                            <label for="form_factor" class="form-label">Форм-фактор</label>
                                            <select class="form-select" id="form_factor" name="form_factor">
                                                <option value="">Не указан</option>
                                                <option value="ATX" {{ old('form_factor', $component->form_factor) == 'ATX' ? 'selected' : '' }}>ATX</option>
                                                <option value="Micro-ATX" {{ old('form_factor', $component->form_factor) == 'Micro-ATX' ? 'selected' : '' }}>Micro-ATX</option>
                                                <option value="Mini-ITX" {{ old('form_factor', $component->form_factor) == 'Mini-ITX' ? 'selected' : '' }}>Mini-ITX</option>
                                                <option value="E-ATX" {{ old('form_factor', $component->form_factor) == 'E-ATX' ? 'selected' : '' }}>E-ATX</option>
                                                <option value="SFX" {{ old('form_factor', $component->form_factor) == 'SFX' ? 'selected' : '' }}>SFX</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="spec-power" style="display: none;">
                                        <div class="mb-3">
                                            <label for="power" class="form-label">Мощность (Вт)</label>
                                            <input type="number" class="form-control" id="power" name="power"
                                                   value="{{ old('power', $component->power) }}" placeholder="Например: 650">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specifications" class="form-label">Полные характеристики</label>
                            <textarea class="form-control @error('specifications') is-invalid @enderror"
                                      id="specifications" name="specifications" rows="3">{{ old('specifications', $component->specifications) }}</textarea>
                            @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Дата покупки *</label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $component->purchase_date->format('Y-m-d')) }}" required>
                                    @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Статус *</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="">Выберите статус...</option>
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $component->status) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('components.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showSpecs() {
                const type = document.getElementById('type').value;

                document.querySelectorAll('[class^="spec-"]').forEach(el => {
                    el.style.display = 'none';
                });

                switch(type) {
                    case 'processor':
                        document.querySelector('.spec-socket').style.display = 'block';
                        document.querySelector('.spec-power').style.display = 'block';
                        break;
                    case 'motherboard':
                        document.querySelector('.spec-socket').style.display = 'block';
                        document.querySelector('.spec-ram_type').style.display = 'block';
                        document.querySelector('.spec-form_factor').style.display = 'block';
                        break;
                    case 'ram':
                        document.querySelector('.spec-ram_type').style.display = 'block';
                        document.querySelector('.spec-power').style.display = 'block';
                        break;
                    case 'psu':
                        document.querySelector('.spec-power').style.display = 'block';
                        document.querySelector('.spec-form_factor').style.display = 'block';
                        break;
                    case 'case':
                        document.querySelector('.spec-form_factor').style.display = 'block';
                        break;
                    case 'storage':
                    case 'gpu':
                    case 'cooler':
                        document.querySelector('.spec-power').style.display = 'block';
                        break;
                }
            }

            document.getElementById('type').addEventListener('change', showSpecs);
            showSpecs();
        </script>
    @endpush
@endsection
