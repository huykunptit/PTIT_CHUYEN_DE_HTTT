<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
        
        foreach ($rooms as $room) {
            // Số hàng và số ghế mỗi hàng phụ thuộc vào capacity
            $capacity = $room->capacity;
            $seatsPerRow = match($room->type) {
                'IMAX' => 20,
                '4DX' => 15,
                'VIP' => 12,
                default => 18,
            };
            
            $numRows = (int) ceil($capacity / $seatsPerRow);
            
            // Giá vé cơ bản theo loại phòng
            $basePrice = match($room->type) {
                'IMAX' => 150000,
                '4DX' => 200000,
                'VIP' => 180000,
                default => 80000,
            };
            
            $seatNumber = 1;
            foreach (array_slice($rows, 0, $numRows) as $rowIndex => $row) {
                for ($col = 1; $col <= $seatsPerRow; $col++) {
                    // Quyết định loại ghế
                    // VIP thường ở hàng giữa (hàng 3-7)
                    // COUPLE ở cuối phòng
                    // STANDARD là phần còn lại
                    $seatType = 'STANDARD';
                    $price = $basePrice;
                    
                    if ($room->type === 'VIP') {
                        $seatType = 'VIP';
                        $price = $basePrice;
                    } else {
                        // Hàng VIP (hàng giữa)
                        if ($rowIndex >= 3 && $rowIndex <= 7 && rand(0, 1)) {
                            $seatType = 'VIP';
                            $price = $basePrice + 50000;
                        }
                        // Ghế đôi (2-3 ghế cuối mỗi hàng)
                        elseif ($col >= ($seatsPerRow - 2) && $col <= $seatsPerRow && rand(0, 2) == 0) {
                            $seatType = 'COUPLE';
                            $price = $basePrice * 2;
                        }
                    }
                    
                    // Tăng giá cho các hàng giữa (view tốt hơn)
                    if ($rowIndex >= 4 && $rowIndex <= 8) {
                        $price += 10000;
                    }
                    
                    Seat::create([
                        'room_id' => $room->id,
                        'row' => $row,
                        'number' => $col,
                        'type' => $seatType,
                        'price' => $price,
                        'is_active' => true,
                    ]);
                    
                    $seatNumber++;
                    
                    // Dừng nếu đã đủ capacity
                    if ($seatNumber > $capacity) {
                        break 2;
                    }
                }
            }
        }
    }
}

