@extends('layouts.app')

@section('title', 'Il Mio Profilo')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4"><i class="bi bi-person-circle"></i> Il Mio Profilo</h2>

        <div class="row">
            <!-- Informazioni Profilo -->
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Dati Personali</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefono</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Indirizzo</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" value="{{ old('address', $user->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h6>Cambia Password (opzionale)</h6>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nuova Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lascia vuoto per non cambiarla</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Conferma Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> Salva Modifiche
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Le Mie Prenotazioni -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Le Mie Prenotazioni</h5>
                    </div>
                    <div class="card-body">
                        @if ($bookings->count() > 0)
                            @foreach ($bookings as $booking)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5>{{ $booking->roomType->name }}</h5>
                                                <p class="mb-1">
                                                    <i class="bi bi-calendar"></i>
                                                    <strong>Check-in:</strong> {{ $booking->check_in->format('d/m/Y') }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-calendar"></i>
                                                    <strong>Check-out:</strong> {{ $booking->check_out->format('d/m/Y') }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-moon"></i>
                                                    <strong>Notti:</strong> {{ $booking->getNights() }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-people"></i>
                                                    <strong>Ospiti:</strong> {{ $booking->num_guests }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-door-closed"></i>
                                                    <strong>Camere:</strong> {{ $booking->num_rooms }}
                                                </p>
                                                @if ($booking->notes)
                                                    <p class="mb-1">
                                                        <i class="bi bi-chat-left-text"></i>
                                                        <strong>Note:</strong> {{ $booking->notes }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <h4 class="text-primary">€{{ number_format($booking->total_price, 2) }}
                                                </h4>

                                                @if ($booking->status === 'pending')
                                                    <span class="badge bg-warning">In Attesa</span>
                                                @elseif($booking->status === 'confirmed')
                                                    <span class="badge bg-success">Confermata</span>
                                                @else
                                                    <span class="badge bg-danger">Cancellata</span>
                                                @endif

                                                @if ($booking->status !== 'cancelled')
                                                    <form method="POST"
                                                        action="{{ route('booking.cancel', $booking->id) }}"
                                                        class="mt-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Sei sicuro di voler cancellare questa prenotazione?')">
                                                            <i class="bi bi-x-circle"></i> Cancella
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Non hai ancora effettuato prenotazioni.
                                <a href="{{ route('home') }}" class="alert-link">Prenota ora!</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
