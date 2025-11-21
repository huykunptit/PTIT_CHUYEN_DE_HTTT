<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = Movie::where('status', 'NOW_SHOWING')->get();
        $rooms = Room::where('is_active', true)->get();
        
        // Tạo lịch chiếu cho 14 ngày tới
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(14);
        
        $timeSlots = [
            ['09:00', '11:30'],  // Sáng sớm
            ['12:00', '14:30'],  // Trưa
            ['14:45', '17:15'],  // Chiều
            ['17:30', '20:00'],  // Tối
            ['20:15', '22:45'],  // Đêm
        ];
        
        foreach ($movies as $movie) {
            // Giảm số suất chiếu: Mỗi phim có 3-5 suất chiếu mỗi ngày (thay vì 5-10)
            $showtimesPerDay = rand(3, 5);
            
            // Chỉ tạo lịch cho 7 ngày tới (thay vì 14 ngày)
            $currentDate = $startDate->copy();
            $demoEndDate = $startDate->copy()->addDays(7);
            while ($currentDate <= $demoEndDate) {
                // Chọn ngẫu nhiên các phòng cho phim này
                $selectedRooms = $rooms->random(min($showtimesPerDay, $rooms->count()));
                
                foreach ($selectedRooms as $index => $room) {
                    // Chọn ngẫu nhiên khung giờ
                    $timeSlot = $timeSlots[array_rand($timeSlots)];
                    $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $timeSlot[0]);
                    $endTime = $startTime->copy()->addMinutes($movie->duration + 15); // +15 phút nghỉ
                    
                    // Kiểm tra xem phòng có bận không (tránh trùng lịch)
                    $conflict = Showtime::where('room_id', $room->id)
                        ->where('date', $currentDate->format('Y-m-d'))
                        ->where(function($query) use ($startTime, $endTime) {
                            $query->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                                  ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                                  ->orWhere(function($q) use ($startTime, $endTime) {
                                      $q->where('start_time', '<=', $startTime->format('H:i:s'))
                                        ->where('end_time', '>=', $endTime->format('H:i:s'));
                                  });
                        })
                        ->exists();
                    
                    if (!$conflict) {
                        Showtime::create([
                            'movie_id' => $movie->id,
                            'room_id' => $room->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => $startTime->format('H:i:s'),
                            'end_time' => $endTime->format('H:i:s'),
                            'status' => 'ACTIVE',
                        ]);
                    }
                }
                
                $currentDate->addDay();
            }
        }
        
        // Tạo lịch chiếu sắp tới cho phim COMING_SOON (bắt đầu từ ngày release)
        $comingSoonMovies = Movie::where('status', 'COMING_SOON')->get();
        
        foreach ($comingSoonMovies as $movie) {
            $releaseDate = Carbon::parse($movie->release_date);
            $endShowDate = $releaseDate->copy()->addDays(3); // Giảm xuống 3 ngày (thay vì 7)
            
            $currentDate = $releaseDate->copy();
            while ($currentDate <= $endShowDate) {
                $showtimesPerDay = rand(2, 4); // Giảm số suất chiếu
                $selectedRooms = $rooms->random(min($showtimesPerDay, $rooms->count()));
                
                foreach ($selectedRooms as $room) {
                    $timeSlot = $timeSlots[array_rand($timeSlots)];
                    $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $timeSlot[0]);
                    $endTime = $startTime->copy()->addMinutes($movie->duration + 15);
                    
                    $conflict = Showtime::where('room_id', $room->id)
                        ->where('date', $currentDate->format('Y-m-d'))
                        ->where(function($query) use ($startTime, $endTime) {
                            $query->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                                  ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')]);
                        })
                        ->exists();
                    
                    if (!$conflict) {
                        Showtime::create([
                            'movie_id' => $movie->id,
                            'room_id' => $room->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => $startTime->format('H:i:s'),
                            'end_time' => $endTime->format('H:i:s'),
                            'status' => 'ACTIVE',
                        ]);
                    }
                }
                
                $currentDate->addDay();
            }
        }
    }
}

