@extends('layouts.app')

@section('title', 'Новое комплектующее')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Добавление нового комплектующего</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('components.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Наименование *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
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
                                           id="model" name="model" value="{{ old('model') }}">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Производитель</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                           id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
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
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                                    @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inventory_number" class="form-label">Инвентарный номер *</label>
                                    <input type="text" class="form-control @error('inventory_number') is-invalid @enderror"
                                           id="inventory_number" name="inventory_number" value="{{ old('inventory_number') }}" required>
                                    @error('inventory_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Технические характеристики для разных типов -->
                        <div class="card mt-3 mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Технические характеристики</h6>
                            </div>
                            <div class="card-body">
                                <div id="tech-specs">
                                    <!-- Сокет (для процессоров и материнских плат) -->
                                    <div class="spec-socket" style="display: none;">
                                        <div class="mb-3">
                                            <label for="socket" class="form-label">Сокет</label>
                                            <select class="form-select" id="socket" name="socket">
                                                <option value="">Не указан</option>
                                                <option value="LGA1700">LGA1700 (Intel 12-14 gen)</option>
                                                <option value="LGA1200">LGA1200 (Intel 10-11 gen)</option>
                                                <option value="AM5">AM5 (AMD Ryzen 7000+)</option>
                                                <option value="AM4">AM4 (AMD Ryzen 1000-5000)</option>
                                                <option value="LGA2066">LGA2066 (Intel Extreme)</option>
                                                <option value="TR4">TR4 (AMD Threadripper)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Тип памяти (для материнских плат и RAM) -->
                                    <div class="spec-ram_type" style="display: none;">
                                        <div class="mb-3">
                                            <label for="ram_type" class="form-label">Тип оперативной памяти</label>
                                            <select class="form-select" id="ram_type" name="ram_type">
                                                <option value="">Не указан</option>
                                                <option value="DDR5">DDR5</option>
                                                <option value="DDR4">DDR4</option>
                                                <option value="DDR3">DDR3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Форм-фактор (для корпусов, материнских плат, БП) -->
                                    <div class="spec-form_factor" style="display: none;">
                                        <div class="mb-3">
                                            <label for="form_factor" class="form-label">Форм-фактор</label>
                                            <select class="form-select" id="form_factor" name="form_factor">
                                                <option value="">Не указан</option>
                                                <option value="ATX">ATX</option>
                                                <option value="Micro-ATX">Micro-ATX</option>
                                                <option value="Mini-ITX">Mini-ITX</option>
                                                <option value="E-ATX">E-ATX</option>
                                                <option value="SFX">SFX (блок питания)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Мощность (для БП и компонентов) -->
                                    <div class="spec-power" style="display: none;">
                                        <div class="mb-3">
                                            <label for="power" class="form-label">Мощность (Вт)</label>
                                            <input type="number" class="form-control" id="power" name="power"
                                                   placeholder="Например: 650" value="{{ old('power') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specifications" class="form-label">Полные характеристики</label>
                            <textarea class="form-control @error('specifications') is-invalid @enderror"
                                      id="specifications" name="specifications" rows="3">{{ old('specifications') }}</textarea>
                            @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Дата покупки *</label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                           id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}" required>
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
                                            <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
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
                                <i class="bi bi-save"></i> Добавить комплектующее
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('type').addEventListener('change', function() {
                const type = this.value;

                // Скрываем все блоки
                document.querySelectorAll('[class^="spec-"]').forEach(el => {
                    el.style.display = 'none';
                });

                // Показываем нужные поля в зависимости от типа
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
            });

            // Вызываем при загрузке
            if (document.getElementById('type').value) {
                document.getElementById('type').dispatchEvent(new Event('change'));
            }
        </script>
    @endpush
@endsection
