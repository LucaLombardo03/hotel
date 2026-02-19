<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Management - Prenota la tua stanza</title>

    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/">🏨 HOTEL LOGO</a>
            <div class="navbar-nav ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="bg-white border-bottom py-5 mb-4">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Trova la tua camera ideale</h1>
            <p class="lead text-muted">Esplora le nostre opzioni per un soggiorno indimenticabile</p>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <form action="{{ route('home') }}" method="GET" class="row g-3 align-items-end text-start">
                        <div class="col-md-3">
                            <label class="form-label fw-medium small text-uppercase">Ospiti</label>
                            <input type="number" name="guests" class="form-control" value="{{ request('guests') }}"
                                placeholder="Es: 2">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium small text-uppercase">Prezzo Max (€)</label>
                            <input type="number" name="max_price" class="form-control"
                                value="{{ request('max_price') }}" placeholder="Es: 150">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium small text-uppercase">Soggiorno</label>
                            <div class="input-group">
                                <input type="date" name="checkin" class="form-control"
                                    value="{{ request('checkin') }}">
                                <input type="date" name="checkout" class="form-control"
                                    value="{{ request('checkout') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">CERCA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="container mb-5">
        <div class="row">
            @forelse($roomTypes as $room)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if ($room->images->count() > 0)
                            <img src="{{ asset('storage/' . $room->images->first()->path) }}" class="card-img-top"
                                alt="{{ $room->name }}">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="height: 200px">
                                Nessuna immagine
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $room->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($room->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary fw-bold">€{{ $room->base_price }}<small
                                        class="text-muted">/notte</small></span>
                                <span class="badge bg-light text-dark border">👤 {{ $room->max_capacity }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <a href="#" class="btn btn-outline-primary w-100">Dettagli camera</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">Nessuna camera trovata con questi filtri.</h3>
                    <a href="{{ route('home') }}" class="btn btn-link">Mostra tutte le camere</a>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="py-4 bg-dark text-white text-center mt-auto">
        <p class="mb-0 small">&copy; {{ date('Y') }} Hotel Management System - Progetto Laravel</p>
    </footer>

</body>

</html>
