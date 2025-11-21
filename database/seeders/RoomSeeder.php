<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cinema;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cinemas = Cinema::all();
        
        $roomTypes = ['STANDARD', 'VIP', 'IMAX', '4DX'];
        
        foreach ($cinemas as $cinema) {
            // Mỗi rạp có 5-8 phòng
            $numRooms = rand(5, 8);
            
            for ($i = 1; $i <= $numRooms; $i++) {
                // Phòng đầu tiên có thể là IMAX hoặc 4DX
                $type = $i == 1 && rand(0, 1) ? ($roomTypes[rand(2, 3)]) : $roomTypes[rand(0, 1)];
                
                // Capacity phụ thuộc vào loại phòng
                $capacity = match($type) {
                    'IMAX' => 300,
                    '4DX' => 150,
                    'VIP' => 100,
                    default => rand(150, 250),
                };
                
                Room::create([
                    'cinema_id' => $cinema->id,
                    'name' => 'Phòng ' . $i . ($type !== 'STANDARD' ? ' (' . $type . ')' : ''),
                    'capacity' => $capacity,
                    'type' => $type,
                    'is_active' => true,
                ]);
            }
        }
    }
}

