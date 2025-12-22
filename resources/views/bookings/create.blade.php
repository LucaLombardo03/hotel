@extends('layouts.app')

@section('title', 'Nuova Prenotazione')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-calendar-plus"></i> Nuova Prenotazione</h4>
                    </div>
                    <div class="card-body">
                        <!-- Info Camera -->
                        <div class="alert alert-info">
                            <h5>{{ $roomType->name }}</h5>
                            <p class="mb-0">{{ $roomType->description }}</p>
                            <p class="mb-0"><strong>Prezzo:</strong> €{{ number_format($roomType->price_per_night, 2) }} /
                                notte</p>
                            <p class="mb-0"><strong>Max Ospiti:</strong> {{ $roomType->max_guests }}</p>
                        </div>

                        <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                            @csrf

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <div class="row">
                                <!-- Check-in -->
                                <div class="col-md-6 mb-3">
                                    <label for="check_in" class="form-label">
                                        <i class="bi bi-calendar"></i> Data Check-in *
                                    </label>
                                    <input type="date" class="form-control @error('check_in') is-invalid @enderror"
                                        id="check_in" name="check_in" value="{{ old('check_in') }}"
                                        min="{{ date('Y-m-d') }}" required>
                                    @error('check_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Check-out -->
                                <div class="col-md-6 mb-3">
                                    <label for="check_out" class="form-label">
                                        <i class="bi bi-calendar"></i> Data Check-out *
                                    </label>
                                    <input type="date" class="form-control @error('check_out') is-invalid @enderror"
                                        id="check_out" name="check_out" value="{{ old('check_out') }}"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('check_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Numero Ospiti -->
                                <div class="col-md-6 mb-3">
                                    <label for="num_guests" class="form-label">
                                        <i class="bi bi-people"></i> Numero Ospiti *
                                    </label>
                                    <input type="number" class="form-control @error('num_guests') is-invalid @enderror"
                                        id="num_guests" name="num_guests" value="{{ old('num_guests', 1) }}" min="1"
                                        max="{{ $roomType->max_guests }}" required>
                                    @error('num_guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Numero Camere -->
                                <div class="col-md-6 mb-3">
                                    <label for="num_rooms" class="form-label">
                                        <i class="bi bi-door-closed"></i> Numero Camere *
                                    </label>
                                    <input type="number" class="form-control @error('num_rooms') is-invalid @enderror"
                                        id="num_rooms" name="num_rooms" value="{{ old('num_rooms', 1) }}" min="1"
                                        max="{{ $roomType->total_rooms }}" required>
                                    @error('num_rooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="bi bi-chat-left-text"></i> Note (opzionale)
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Riepilogo Prezzo (dinamico con JS) -->
                            <div class="alert alert-success" id="pricePreview" style="display: none;">
                                <h5>Riepilogo Prenotazione:</h5>
                                <p class="mb-0"><strong>Notti:</strong> <span id="nights">0</span></p>
                                <p class="mb-0"><strong>Camere:</strong> <span id="roomsCount">1</span></p>
                                <h4 class="mt-2 mb-0">Totale: €<span id="totalPrice">0.00</span></h4>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Conferma Prenotazione
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Torna Indietro
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const pricePerNight = {{ $roomType->price_per_night }};

            function calculatePrice() {
                const checkIn = document.getElementById('check_in').value;
                const checkOut = document.getElementById('check_out').value;
                const numRooms = parseInt(document.getElementById('num_rooms').value) || 1;

                if (checkIn && checkOut) {
                    const date1 = new Date(checkIn);
                    const date2 = new Date(checkOut);
                    const diffTime = Math.abs(date2 - date1);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 0) {
                        const totalPrice = pricePerNight * diffDays * numRooms;

                        document.getElementById('nights').textContent = diffDays;
                        document.getElementById('roomsCount').textContent = numRooms;
                        document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
                        document.getElementById('pricePreview').style.display = 'block';
                    }
                }
            }

            document.getElementById('check_in').addEventListener('change', calculatePrice);
            document.getElementById('check_out').addEventListener('change', calculatePrice);
            document.getElementById('num_rooms').addEventListener('input', calculatePrice);
        </script>
    @endpush
@endsection
