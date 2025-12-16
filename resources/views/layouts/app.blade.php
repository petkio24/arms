<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Учет АРМ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
@auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-pc-display-horizontal"></i> Учет АРМ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}"
                           href="{{ url('/') }}">
                            <i class="bi bi-speedometer2"></i> Дашборд
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('locations*') ? 'active' : '' }}"
                           href="{{ route('locations.index') }}">
                            <i class="bi bi-building"></i> Помещения
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('workstations*') ? 'active' : '' }}"
                           href="{{ route('workstations.index') }}">
                            <i class="bi bi-pc-display"></i> Рабочие станции
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('components*') ? 'active' : '' }}"
                           href="{{ route('components.index') }}">
                            <i class="bi bi-motherboard"></i> Комплектующие
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Выйти
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endauth

<main class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

@auth
    <footer class="bg-light mt-5 py-3 border-top">
        <div class="container text-center text-muted">
            <small>Система учета комплектующих и сборки рабочих станций &copy; {{ date('Y') }}</small>
        </div>
    </footer>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
