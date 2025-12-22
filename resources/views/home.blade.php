@extends('layouts.app')

@section('title', 'Home - Hotel')

{{-- Aggiungiamo lo stile specifico per questa pagina --}}
@push('styles')
    <style>
        /* --- PERSONALIZZAZIONE CAROSELLO --- */

        /* 1. FRECCE (Prev/Next) */
        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            /* Area cliccabile */
            opacity: 1;
            /* Rimuove opacità di default di Bootstrap */
        }

        /* Lo sfondo del cerchio delle frecce */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(44, 62, 80, 0.75);
            /* Colore Primario (Blu Scuro) con leggera trasparenza */
            border-radius: 50%;
            /* Li rende rotondi */
            width: 50px;
            height: 50px;
            background-size: 50%;
            /* Dimensione della freccia interna */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            /* Ombra per staccare dallo sfondo */
            transition: all 0.3s ease;
        }

        /* Effetto Hover sulle frecce */
        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            background-color: var(--secondary-color);
            /* Diventa Azzurro (#3498db) */
            transform: scale(1.1);
            /* Leggero zoom */
        }

        /* 2. INDICATORI (Pallini in basso) */
        .carousel-indicators {
            margin-bottom: 1.5rem;
        }

        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            /* Pallino rotondo */
            background-color: #fff;
            /* Interno bianco */
            border: 2px solid var(--primary-color);
            /* Bordo scuro per contrasto */
            opacity: 0.6;
            margin: 0 6px;
            transition: all 0.3s ease;
        }

        /* Pallino Attivo */
        .carousel-indicators .active {
            opacity: 1;
            background-color: var(--secondary-color);
            /* Azzurro pieno */
            border-color: var(--secondary-color);
            transform: scale(1.25);
            /* Più grande */
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
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
                    @else
                        <div class="alert alert-secondary mb-4">
                            <i class="bi bi-image"></i> Nessuna immagine disponibile per questo hotel.
                        </div>
                    @endif

                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h3><i class="bi bi-info-circle text-primary"></i> Descrizione</h3>
                            <p class="mb-0">{{ $hotel->description }}</p>
                        </div>
                    </div>

                    <h3 class="mb-4 pt-3"><i class="bi bi-door-open text-primary"></i> Le Nostre Camere</h3>

                    @if ($hotel->roomTypes->count() > 0)
                        <div class="row">
                            @foreach ($hotel->roomTypes as $roomType)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column">
                                            <h4 class="card-title">{{ $roomType->name }}</h4>
                                            <p class="card-text text-muted small flex-grow-1">{{ $roomType->description }}
                                            </p>

                                            <div class="mb-3">
                                                <span class="badge bg-info text-dark">
                                                    <i class="bi bi-people"></i> Max {{ $roomType->max_guests }} ospiti
                                                </span>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-door-closed"></i> {{ $roomType->total_rooms }} camere
                                                </span>
                                            </div>

                                            <h5 class="text-primary fw-bold">
                                                €{{ number_format($roomType->price_per_night, 2) }} <small
                                                    class="text-muted fw-normal">/ notte</small></h5>

                                            @auth
                                                <a href="{{ route('booking.create', ['room_type_id' => $roomType->id]) }}"
                                                    class="btn btn-primary w-100 mt-3">
                                                    <i class="bi bi-calendar-check"></i> Prenota Ora
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-3">
                                                    <i class="bi bi-box-arrow-in-right"></i> Accedi per Prenotare
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nessuna camera disponibile al momento.
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card sticky-top shadow-sm border-0" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Contatti & Servizi</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <strong><i class="bi bi-map text-primary"></i> Indirizzo:</strong><br>
                                    {{ $hotel->address }}
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-telephone text-primary"></i> Telefono:</strong><br>
                                    {{ $hotel->phone }}
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-envelope text-primary"></i> Email:</strong><br>
                                    {{ $hotel->email }}
                                </li>
                            </ul>

                            <hr>

                            @if ($hotel->amenities)
                                <h6 class="fw-bold"><i class="bi bi-stars text-warning"></i> Servizi Inclusi:</h6>
                                <ul class="list-unstyled">
                                    @foreach ($hotel->amenities as $amenity)
                                        <li class="mb-1"><i class="bi bi-check2-circle text-success"></i>
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
        <div class="container my-5">
            <div class="alert alert-warning text-center shadow-sm">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-3">Hotel non configurato</h3>
                <p>L'amministratore deve ancora configurare le informazioni dell'hotel.</p>
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-gear"></i> Vai alla Dashboard Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    @endif
@endsection
