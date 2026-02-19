<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // ========== CREA UTENTE ADMIN ==========
        $admin = User::create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+39 0521 123456',
            'address' => 'Via Roma 1, Parma',
        ]);

        echo "✅ Admin creato: admin@hotel.com / password123\n";

        // ========== CREA UTENTE NORMALE ==========
        $user1 = User::create([
            'name' => 'Mario Rossi',
            'email' => 'mario@email.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+39 333 1234567',
            'address' => 'Via Milano 10, Parma',
        ]);

        $user2 = User::create([
            'name' => 'Giulia Bianchi',
            'email' => 'giulia@email.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+39 333 7654321',
            'address' => 'Corso Garibaldi 5, Parma',
        ]);

        echo "✅ Utenti creati: mario@email.com e giulia@email.com / password123\n";

        // ========== CREA HOTEL ==========
        $hotel = Hotel::create([
            'name' => 'Grand Hotel Parma',
            'description' => 'Un elegante hotel 4 stelle nel cuore di Parma, a pochi passi dal Teatro Regio e dal Duomo. Offriamo camere spaziose e moderne, una colazione a buffet con prodotti locali e un servizio clienti impeccabile. Ideale per viaggi di lavoro e di piacere.',
            'address' => 'Piazza Duomo 7, 43121 Parma PR',
            'phone' => '+39 0521 987654',
            'email' => 'info@grandhotelparma.it',
            'stars' => 4,
            'amenities' => [
                'Wi-Fi Gratuito',
                'Parcheggio',
                'Colazione Inclusa',
                'Aria Condizionata',
                'TV Satellitare',
                'Reception 24h',
            ],
        ]);

        echo "✅ Hotel creato: {$hotel->name}\n";

        // ========== CREA TIPOLOGIE CAMERE ==========
        $cameraSingola = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Camera Singola Standard',
            'description' => 'Camera accogliente con letto singolo, scrivania, bagno privato con doccia. Ideale per viaggiatori singoli.',
            'price_per_night' => 65.00,
            'max_guests' => 1,
            'total_rooms' => 10,
            'amenities' => ['Wi-Fi', 'TV', 'Aria Condizionata'],
        ]);

        $cameraDoppia = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Camera Doppia Comfort',
            'description' => 'Spaziosa camera con letto matrimoniale o due letti singoli, bagno con vasca, minibar. Perfetta per coppie.',
            'price_per_night' => 95.00,
            'max_guests' => 2,
            'total_rooms' => 15,
            'amenities' => ['Wi-Fi', 'TV', 'Minibar', 'Cassaforte'],
        ]);

        $cameraFamiliare = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Camera Familiare',
            'description' => 'Grande camera con due letti matrimoniali, area soggiorno, bagno spazioso. Ideale per famiglie con bambini.',
            'price_per_night' => 140.00,
            'max_guests' => 4,
            'total_rooms' => 8,
            'amenities' => ['Wi-Fi', 'TV', 'Minibar', 'Area Giochi', 'Lettino Bambini'],
        ]);

        $suite = RoomType::create([
            'hotel_id' => $hotel->id,
            'name' => 'Suite Deluxe',
            'description' => 'Lussuosa suite con camera da letto separata, salotto elegante, bagno con jacuzzi, terrazza panoramica. Il massimo del comfort.',
            'price_per_night' => 220.00,
            'max_guests' => 2,
            'total_rooms' => 4,
            'amenities' => ['Wi-Fi', 'TV', 'Minibar', 'Jacuzzi', 'Terrazza', 'Accappatoi'],
        ]);

        echo "✅ Tipologie camere create (4 tipologie)\n";

        // ========== CREA PRENOTAZIONI DI ESEMPIO ==========
        Booking::create([
            'user_id' => $user1->id,
            'room_type_id' => $cameraDoppia->id,
            'check_in' => now()->addDays(5),
            'check_out' => now()->addDays(8),
            'num_guests' => 2,
            'num_rooms' => 1,
            'total_price' => 285.00,
            'status' => 'confirmed',
            'notes' => 'Arrivo previsto in serata',
        ]);

        Booking::create([
            'user_id' => $user2->id,
            'room_type_id' => $suite->id,
            'check_in' => now()->addDays(10),
            'check_out' => now()->addDays(12),
            'num_guests' => 2,
            'num_rooms' => 1,
            'total_price' => 440.00,
            'status' => 'pending',
            'notes' => 'Viaggio di nozze - richiesta champagne in camera',
        ]);

        Booking::create([
            'user_id' => $user1->id,
            'room_type_id' => $cameraFamiliare->id,
            'check_in' => now()->addDays(15),
            'check_out' => now()->addDays(20),
            'num_guests' => 4,
            'num_rooms' => 1,
            'total_price' => 700.00,
            'status' => 'pending',
            'notes' => 'Vacanza con bambini di 6 e 9 anni',
        ]);

        echo "✅ Prenotazioni di esempio create (3)\n";

        echo "\n";
        echo "=========================================\n";
        echo "✨ DATABASE POPOLATO CON SUCCESSO! ✨\n";
        echo "=========================================\n";
        echo "\n";
        echo "CREDENZIALI ACCESSO:\n";
        echo "--------------------\n";
        echo "👤 ADMIN:\n";
        echo "   Email: admin@hotel.com\n";
        echo "   Password: password123\n";
        echo "\n";
        echo "👤 UTENTI:\n";
        echo "   Email: mario@email.com\n";
        echo "   Password: password123\n";
        echo "\n";
        echo "   Email: giulia@email.com\n";
        echo "   Password: password123\n";
        echo "=========================================\n";
    }
}
