<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Cinema::create([
            'name' => 'Cinema Landmark 81',
            'address' => 'Tầng 3, Landmark 81, Vinhomes Central Park, Q. Bình Thạnh, TP.HCM',
            'phone' => '028 7300 1881',
            'email' => 'landmark81@cinema.com',
            'description' => 'Rạp chiếu phim hiện đại với công nghệ IMAX và 4DX',
            'is_active' => true,
        ]);
        
        \App\Models\Cinema::create([
            'name' => 'Cinema Vincom Center',
            'address' => 'Tầng 4, Vincom Center, 72 Lê Thánh Tôn, Q.1, TP.HCM',
            'phone' => '028 3822 8899',
            'email' => 'vincom@cinema.com',
            'description' => 'Rạp chiếu phim tại trung tâm thành phố',
            'is_active' => true,
        ]);
    }
}
