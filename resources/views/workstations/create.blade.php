@extends('layouts.app')

@section('title', 'Новая рабочая станция')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Добавление новой рабочей станции</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('workstations.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Название *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inventory_number" class="form-label">Инвентарный номер *</label>
                            <input type="text" class="form-control @error('inventory_number') is-invalid @enderror"
                                   id="inventory_number" name="inventory_number"
                                   value="{{ old('inventory_number') }}" required>
                            @error('inventory_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location_id" class="form-label">Помещение</label>
                            <div class="input-group">
                                <select class="form-select @error('location_id') is-invalid @enderror"
                                        id="location_id" name="location_id">
                                    <option value="">Выберите помещение...</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                            {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                            @if($location->room)
                                                ({{ $location->room }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('locations.create') }}?redirect_to=workstations.create"
                                   class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-plus"></i> Новое
                                </a>
                            </div>
                            @error('location_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="mb-3">
                            <label for="notes" class="form-label">Примечания</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('workstations.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Добавить станцию
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Проверяем, есть ли ID нового помещения в сессии
            const newLocationId = "{{ session('new_location_id') }}";
            if (newLocationId) {
                const locationSelect = document.getElementById('location_id');
                if (locationSelect) {
                    locationSelect.value = newLocationId;

                    // Показываем сообщение, что помещение выбрано автоматически
                    const successAlert = document.querySelector('.alert-success');
                    if (successAlert) {
                        successAlert.textContent += ' Новое помещение выбрано автоматически.';
                    }
                }
            }
        });
    </script>
@endpush
