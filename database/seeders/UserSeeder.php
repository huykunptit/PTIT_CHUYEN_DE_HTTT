<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '0900000001',
            'is_member' => true,
            'role' => 'admin',
        ]);

        // Sample users
        $names = [
            'Nguyễn Văn A', 'Trần Thị B', 'Lê Văn C', 'Phạm Thị D', 'Hoàng Văn E',
            'Vũ Thị F', 'Đặng Văn G', 'Bùi Thị H', 'Đỗ Văn I', 'Hồ Thị K',
            'Ngô Văn L', 'Dương Thị M', 'Lý Văn N', 'Trịnh Thị O', 'Phan Văn P',
            'Tôn Thị Q', 'Võ Văn R', 'Đinh Thị S', 'Chu Văn T', 'Cao Thị U',
        ];

        foreach ($names as $index => $name) {
            $email = 'user' . ($index + 1) . '@cinema.com';
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => '09' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'date_of_birth' => now()->subYears(rand(18, 50))->subDays(rand(0, 365)),
                'is_member' => rand(0, 1) == 1,
            ]);
        }
    }
}

