@extends('layouts.app')

@section('title', 'Home - Hotel')

@section('content')
    @if ($hotel)
        <!-- Hero Section -->
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

        <!-- Informazioni Hotel -->
        <div class="container my-5">
            <div class="row">
                <div class="col-md-8">
                    <!-- Galleria Immagini -->
                    @if ($hotel->images->count() > 0)
                        <div class="row mb-4">
                            @foreach ($hotel->images as $image)
                                <div class="col-md-6 mb-3">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded shadow"
                                        alt="Hotel Image" style="width: 100%; height: 300px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Descrizione -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3><i class="bi bi-info-circle text-primary"></i> Descrizione</h3>
                            <p>{{ $hotel->description }}</p>
                        </div>
                    </div>

                    <!-- Camere Disponibili -->
                    <h3 class="mb-4"><i class="bi bi-door-open text-primary"></i> Le Nostre Camere</h3>

                    @if ($hotel->roomTypes->count() > 0)
                        <div class="row">
                            @foreach ($hotel->roomTypes as $roomType)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $roomType->name }}</h4>
                                            <p class="card-text">{{ $roomType->description }}</p>

                                            <div class="mb-3">
                                                <span class="badge bg-info">
                                                    <i class="bi bi-people"></i> Max {{ $roomType->max_guests }} ospiti
                                                </span>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-door-closed"></i> {{ $roomType->total_rooms }} camere
                                                </span>
                                            </div>

                                            <h5 class="text-primary">€{{ number_format($roomType->price_per_night, 2) }} /
                                                notte</h5>

                                            @auth
                                                <a href="{{ route('booking.create', ['room_type_id' => $roomType->id]) }}"
                                                    class="btn btn-primary w-100 mt-3">
                                                    <i class="bi bi-calendar-check"></i> Prenota Ora
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-secondary w-100 mt-3">
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

                <!-- Sidebar Info Contatto -->
                <div class="col-md-4">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Informazioni Contatto</h5>
                        </div>
                        <div class="card-body">
                            <p><strong><i class="bi bi-map"></i> Indirizzo:</strong><br>{{ $hotel->address }}</p>
                            <p><strong><i class="bi bi-telephone"></i> Telefono:</strong><br>{{ $hotel->phone }}</p>
                            <p><strong><i class="bi bi-envelope"></i> Email:</strong><br>{{ $hotel->email }}</p>

                            <hr>

                            @if ($hotel->amenities)
                                <h6><i class="bi bi-star"></i> Servizi:</h6>
                                <ul>
                                    @foreach ($hotel->amenities as $amenity)
                                        <li>{{ $amenity }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Nessun Hotel Configurato -->
        <div class="container my-5">
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                <h3 class="mt-3">Hotel non configurato</h3>
                <p>L'amministratore deve ancora configurare le informazioni dell'hotel.</p>
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-gear"></i> Vai alla Dashboard Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    @endif
@endsection
