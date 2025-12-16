@extends('layouts.app')

@section('title', 'Помещения')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Помещения</h1>
        <a href="{{ route('locations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Добавить
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Адрес</th>
                        <th>Ответственный</th>
                        <th>Контакты</th>
                        <th>Рабочих станций</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>
                                <strong>{{ $location->name }}</strong>
                                @if($location->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($location->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($location->full_address != 'Адрес не указан')
                                    {{ $location->full_address }}
                                @else
                                    <span class="text-muted">Не указано</span>
                                @endif
                            </td>
                            <td>
                                @if($location->responsible_person)
                                    {{ $location->responsible_person }}
                                @else
                                    <span class="text-muted">Не назначен</span>
                                @endif
                            </td>
                            <td>
                                @if($location->phone || $location->email)
                                    @if($location->phone)
                                        <div><i class="bi bi-telephone"></i> {{ $location->phone }}</div>
                                    @endif
                                    @if($location->email)
                                        <div><i class="bi bi-envelope"></i> {{ $location->email }}</div>
                                    @endif
                                @else
                                    <span class="text-muted">Не указаны</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $location->workstations_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('locations.show', $location) }}"
                                       class="btn btn-outline-info" title="Просмотр">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('locations.edit', $location) }}"
                                       class="btn btn-outline-primary" title="Редактировать">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('locations.destroy', $location) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                title="Удалить"
                                                onclick="return confirm('Удалить помещение?')">
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
                {{ $locations->links() }}
            </div>
        </div>
    </div>
@endsection
