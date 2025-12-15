@extends('layouts.app')

@section('title', isset($component) ? 'Редактирование ' . $component->name : 'Новое комплектующее')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ isset($component) ? 'Редактирование комплектующего' : 'Добавление нового комплектующего' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($component) ? route('components.update', $component) : route('components.store') }}"
                          method="POST">
                        @csrf
                        @if(isset($component))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Наименование *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $component->name ?? '') }}" required>
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
                                            <option value="{{ $value }}"
                                                {{ old('type', $component->type ?? '') == $value ? 'selected' : '' }}>
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
                                           id="model" name="model"
                                           value="{{ old('model', $component->model ?? '') }}">
                                    @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Производитель</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                                           id="manufacturer" name="manufacturer"
                                           value="{{ old('manufacturer', $component->manufacturer ?? '') }}">
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
                                           id="serial_number" name="serial_number"
                                           value="{{ old('serial_number', $component->serial_number ?? '') }}" required>
                                    @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inventory_number" class="form-label">Инвентарный номер *</label>
                                    <input type="text" class="form-control @error('inventory_number') is-invalid @enderror"
                                           id="inventory_number" name="inventory_number"
                                           value="{{ old('inventory_number', $component->inventory_number ?? '') }}" required>
                                    @error('inventory_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specifications" class="form-label">Характеристики</label>
                            <textarea class="form-control @error('specifications') is-invalid @enderror"
                                      id="specifications" name="specifications"
                                      rows="3">{{ old('specifications', $component->specifications ?? '') }}</textarea>
                            @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Дата покупки *</label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                           id="purchase_date" name="purchase_date"
                                           value="{{ old('purchase_date', isset($component) ? $component->purchase_date->format('Y-m-d') : '') }}" required>
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
                                            <option value="{{ $value }}"
                                                {{ old('status', $component->status ?? '') == $value ? 'selected' : '' }}>
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
                                <i class="bi bi-save"></i>
                                {{ isset($component) ? 'Сохранить изменения' : 'Добавить комплектующее' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
