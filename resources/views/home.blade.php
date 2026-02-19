@extends('layouts.app')

@section('title', 'Home - Hotel')

@push('styles')
    <style>
        /* --- PERSONALIZZAZIONE CAROSELLO --- */
        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            opacity: 1;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(44, 62, 80, 0.75);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            background-size: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            background-color: var(--secondary-color);
            transform: scale(1.1);
        }

        .carousel-indicators {
            margin-bottom: 1.5rem;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid var(--primary-color);
            opacity: 0.6;
            margin: 0 6px;
            transition: all 0.3s ease;
        }

        .carousel-indicators .active {
            opacity: 1;
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: scale(1.25);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        }

        /* Stile barra di ricerca */
        .search-bar {
            background: #f8f9fa;
            border-radius: 15px;
            border: 1px solid #e9ecef;
        }
    </style>
@endpush

@section('content')
    @if ($hotel)
        <div class="hero-section">
            <div class="container">
                <h1 class="display-3 fw-bold mb-3">{{ $hotel->name }}</h1>
                <p class="lead mb-4">
                    <span class="stars">
                        @for ($i = 0; $i < $hotel->stars; $i++)
                            <i class="bi bi-star-fill"></i>
                        @endfor
                    </span>
                </p>
                <p class="lead">{{ $hotel->description }}</p>
            </div>
        </div>

        <div class="container my-5">
            <div class="row">
                <div class="col-md-8">

                    @if ($hotel->images->count() > 0)
                        <div id="hotelCarousel" class="carousel slide mb-4 shadow rounded overflow-hidden"
                            data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach ($hotel->images as $key => $image)
                                    <button type="button" data-bs-target="#hotelCarousel"
                                        data-bs-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}"
                                        aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $key + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($hotel->images as $image)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100"
                                            alt="Foto Hotel" style="height: 450px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Precedente</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Successivo</span>
                            </button>
                        </div>
                    @endif

                    {{-- --- BARRA DI RICERCA / FILTRI --- --}}
                    <div class="card mb-5 shadow-sm border-0 search-bar">
                        <div class="card-body p-4">
                            <h4 class="mb-3"><i class="bi bi-search text-primary"></i> Cerca Disponibilità</h4>
                            <form action="{{ route('home') }}" method="GET" class="row g-3">
                                <div class="col-md-5">
                                    <label class="small fw-bold text-uppercase">Numero Ospiti</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-people"></i></span>
                                        <input type="number" name="guests" class="form-control"
                                            value="{{ request('guests') }}" placeholder="Quanti siete?">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="small fw-bold text-uppercase">Budget Max per notte</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-currency-euro"></i></span>
                                        <input type="number" name="max_price" class="form-control"
                                            value="{{ request('max_price') }}" placeholder="Esempio: 200">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
                                        <i class="bi bi-sliders"></i>
                                    </button>
                                </div>
                                @if (request('guests') || request('max_price'))
                                    <div class="col-12 mt-2">
                                        <a href="{{ route('home') }}" class="text-decoration-none small text-muted">
                                            <i class="bi bi-x-circle"></i> Rimuovi tutti i filtri
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <h3 class="mb-4 pt-3"><i class="bi bi-door-open text-primary"></i> Le Nostre Camere</h3>

                    {{-- --- LISTA CAMERE FILTRATA --- --}}
                    @if ($roomTypes->count() > 0)
                        <div class="row">
                            @foreach ($roomTypes as $roomType)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0 transition-hover">
                                        <div class="card-body d-flex flex-column">
                                            <h4 class="card-title fw-bold">{{ $roomType->name }}</h4>
                                            <p class="card-text text-muted small flex-grow-1">{{ $roomType->description }}
                                            </p>

                                            <div class="mb-3 mt-2">
                                                <span class="badge bg-info text-dark">
                                                    <i class="bi bi-people"></i> Max {{ $roomType->max_guests }} ospiti
                                                </span>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-door-closed"></i> {{ $roomType->total_rooms }}
                                                    disponibili
                                                </span>
                                            </div>

                                            <h5 class="text-primary fw-bold mb-3">
                                                €{{ number_format($roomType->price_per_night, 2) }}
                                                <small class="text-muted fw-normal">/ notte</small>
                                            </h5>

                                            @auth
                                                <a href="{{ route('booking.create', ['room_type_id' => $roomType->id]) }}"
                                                    class="btn btn-primary w-100">
                                                    <i class="bi bi-calendar-check"></i> Prenota Ora
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                                                    <i class="bi bi-box-arrow-in-right"></i> Accedi per Prenotare
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info py-4 text-center">
                            <i class="bi bi-search" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Nessuna camera corrisponde ai criteri di ricerca selezionati.</p>
                            <a href="{{ route('home') }}" class="fw-bold text-primary">Mostra tutte le camere</a>
                        </div>
                    @endif
                </div>

                {{-- --- COLONNA LATERALE CONTATTI --- --}}
                <div class="col-md-4">
                    <div class="card sticky-top shadow-sm border-0" style="top: 20px;">
                        <div class="card-header bg-primary text-white p-3">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Contatti & Servizi</h5>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <strong><i class="bi bi-map text-primary"></i> Indirizzo:</strong><br>
                                    <span class="text-muted">{{ $hotel->address }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-telephone text-primary"></i> Telefono:</strong><br>
                                    <span class="text-muted">{{ $hotel->phone }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-envelope text-primary"></i> Email:</strong><br>
                                    <span class="text-muted">{{ $hotel->email }}</span>
                                </li>
                            </ul>
                            <hr class="my-4">
                            @if ($hotel->amenities)
                                <h6 class="fw-bold mb-3"><i class="bi bi-stars text-warning"></i> Servizi Inclusi:</h6>
                                <ul class="list-unstyled">
                                    @foreach ($hotel->amenities as $amenity)
                                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>
                                            {{ $amenity }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container my-5 py-5 text-center">
            <div class="alert alert-warning shadow-sm d-inline-block px-5">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-3">Hotel non configurato</h3>
                <p>Configura le informazioni dell'hotel nella dashboard amministratore.</p>
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-2">Dashboard Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    @endif
@endsection
