@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
    <style>
        .admin-section {
            margin-bottom: 40px;
        }

        .stat-card {
            border-left: 4px solid;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateX(5px);
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-speedometer2"></i> Dashboard Amministratore</h2>
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="bi bi-eye"></i> Visualizza Sito
            </a>
        </div>

        <!-- ========== SEZIONE HOTEL ========== -->
        <div class="admin-section">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Gestione Hotel</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.hotel.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome Hotel *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $hotel->name ?? '' }}" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="stars" class="form-label">Stelle *</label>
                                <select class="form-select" id="stars" name="stars" required>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ ($hotel->stars ?? 3) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'Stella' : 'Stelle' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="phone" class="form-label">Telefono *</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ $hotel->phone ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ $hotel->email ?? '' }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Indirizzo *</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $hotel->address ?? '' }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrizione *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ $hotel->description ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salva Informazioni Hotel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========== SEZIONE IMMAGINI ========== -->
        <div class="admin-section">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-images"></i> Galleria Immagini</h5>
                </div>
                <div class="card-body">
                    <!-- Form Upload -->
                    <form method="POST" action="{{ route('admin.images.upload') }}" enctype="multipart/form-data"
                        class="mb-4">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3">
                                <label for="image" class="form-label">Carica Nuova Immagine</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                    required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_main" name="is_main"
                                        value="1">
                                    <label class="form-check-label" for="is_main">
                                        Immagine Principale
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-upload"></i> Carica
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Galleria Immagini -->
                    @if ($hotel && $hotel->images->count() > 0)
                        <div class="row">
                            @foreach ($hotel->images as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                            style="height: 200px; object-fit: cover;" alt="Hotel Image">
                                        <div class="card-body p-2">
                                            @if ($image->is_main)
                                                <span class="badge bg-primary w-100 mb-2">Principale</span>
                                            @endif
                                            <form method="POST" action="{{ route('admin.images.delete', $image->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger w-100"
                                                    onclick="return confirm('Eliminare questa immagine?')">
                                                    <i class="bi bi-trash"></i> Elimina
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nessuna immagine caricata.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========== SEZIONE TIPOLOGIE CAMERE ========== -->
        <div class="admin-section">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-door-open"></i> Gestione Camere</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="bi bi-plus-circle"></i> Aggiungi Camera
                    </button>
                </div>
                <div class="card-body">
                    @if ($hotel && $hotel->roomTypes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrizione</th>
                                        <th>Prezzo/Notte</th>
                                        <th>Max Ospiti</th>
                                        <th>Totale Camere</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotel->roomTypes as $roomType)
                                        <tr>
                                            <td><strong>{{ $roomType->name }}</strong></td>
                                            <td>{{ Str::limit($roomType->description, 50) }}</td>
                                            <td>€{{ number_format($roomType->price_per_night, 2) }}</td>
                                            <td><i class="bi bi-people"></i> {{ $roomType->max_guests }}</td>
                                            <td><i class="bi bi-door-closed"></i> {{ $roomType->total_rooms }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editRoomModal{{ $roomType->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('admin.room-types.delete', $roomType->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Eliminare questa tipologia?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Modifica -->
                                        <div class="modal fade" id="editRoomModal{{ $roomType->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('admin.room-types.update', $roomType->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Modifica Camera</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nome</label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ $roomType->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Descrizione</label>
                                                                <textarea class="form-control" name="description" rows="3" required>{{ $roomType->description }}</textarea>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Prezzo/Notte</label>
                                                                    <input type="number" class="form-control"
                                                                        name="price_per_night"
                                                                        value="{{ $roomType->price_per_night }}"
                                                                        step="0.01" required>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Max Ospiti</label>
                                                                    <input type="number" class="form-control"
                                                                        name="max_guests"
                                                                        value="{{ $roomType->max_guests }}" required>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Tot. Camere</label>
                                                                    <input type="number" class="form-control"
                                                                        name="total_rooms"
                                                                        value="{{ $roomType->total_rooms }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Chiudi</button>
                                                            <button type="submit" class="btn btn-primary">Salva</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nessuna tipologia camera creata.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========== SEZIONE PRENOTAZIONI ========== -->
        <div class="admin-section">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Gestione Prenotazioni</h5>
                </div>
                <div class="card-body">
                    @if ($bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Camera</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Ospiti</th>
                                        <th>Totale</th>
                                        <th>Stato</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->roomType->name }}</td>
                                            <td>{{ $booking->check_in->format('d/m/Y') }}</td>
                                            <td>{{ $booking->check_out->format('d/m/Y') }}</td>
                                            <td>{{ $booking->num_guests }}</td>
                                            <td>€{{ number_format($booking->total_price, 2) }}</td>
                                            <td>
                                                @if ($booking->status === 'pending')
                                                    <span class="badge bg-warning">In Attesa</span>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="badge bg-success">Confermata</span>
                                                @else
                                                    <span class="badge bg-danger">Cancellata</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->status !== 'cancelled')
                                                    <form method="POST"
                                                        action="{{ route('admin.bookings.update-status', $booking->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status"
                                                            class="form-select form-select-sm d-inline-block w-auto"
                                                            onchange="this.form.submit()">
                                                            <option value="pending"
                                                                {{ $booking->status === 'pending' ? 'selected' : '' }}>In
                                                                Attesa</option>
                                                            <option value="confirmed"
                                                                {{ $booking->status === 'confirmed' ? 'selected' : '' }}>
                                                                Conferma</option>
                                                            <option value="cancelled"
                                                                {{ $booking->status === 'cancelled' ? 'selected' : '' }}>
                                                                Cancella</option>
                                                        </select>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginazione -->
                        <div class="mt-3">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nessuna prenotazione presente.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aggiungi Camera -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.room-types.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Aggiungi Nuova Tipologia Camera</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text" class="form-control" name="name" placeholder="es. Camera Doppia"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrizione *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prezzo/Notte *</label>
                                <input type="number" class="form-control" name="price_per_night" step="0.01"
                                    min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Max Ospiti *</label>
                                <input type="number" class="form-control" name="max_guests" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Totale Camere *</label>
                                <input type="number" class="form-control" name="total_rooms" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                        <button type="submit" class="btn btn-primary">Aggiungi Camera</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
