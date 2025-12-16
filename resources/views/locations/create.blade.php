@extends('layouts.app')

@section('title', 'Новое помещение')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Добавление нового помещения</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="redirect_to" value="{{ $redirectTo ?? 'locations.index' }}">
                        @if(request()->has('workstation_id'))
                            <input type="hidden" name="workstation_id" value="{{ request()->workstation_id }}">
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Название помещения *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="building" class="form-label">Корпус/Здание</label>
                                    <input type="text" class="form-control @error('building') is-invalid @enderror"
                                           id="building" name="building"
                                           value="{{ old('building') }}">
                                    @error('building')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Этаж</label>
                                    <input type="text" class="form-control @error('floor') is-invalid @enderror"
                                           id="floor" name="floor"
                                           value="{{ old('floor') }}">
                                    @error('floor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="room" class="form-label">Кабинет/Комната</label>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror"
                                           id="room" name="room"
                                           value="{{ old('room') }}">
                                    @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="responsible_person" class="form-label">Ответственное лицо</label>
                            <input type="text" class="form-control @error('responsible_person') is-invalid @enderror"
                                   id="responsible_person" name="responsible_person"
                                   value="{{ old('responsible_person') }}">
                            @error('responsible_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Добавить помещение
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
