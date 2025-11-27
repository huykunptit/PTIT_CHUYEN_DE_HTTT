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

        // Staff users
        User::create([
            'name' => 'Nguyễn Quốc Huy',
            'email' => 'huy@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '0901234567',
            'is_member' => true,
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Nguyễn Doãn Hải',
            'email' => 'hai@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '0901234568',
            'is_member' => true,
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Phạm Thị Hằng',
            'email' => 'hang@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '0901234569',
            'is_member' => true,
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Trần Ngọc Hưng',
            'email' => 'hung@cinema.com',
            'password' => Hash::make('password'),
            'phone' => '0901234570',
            'is_member' => true,
            'role' => 'staff',
        ]);

        // Test users với thông tin cụ thể
        User::create([
            'name' => 'Nguyễn Văn An',
            'email' => 'user1@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345678',
            'date_of_birth' => now()->subYears(25)->subMonths(3),
            'is_member' => true,
        ]);

        User::create([
            'name' => 'Trần Thị Bình',
            'email' => 'user2@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345679',
            'date_of_birth' => now()->subYears(30)->subMonths(5),
            'is_member' => true,
        ]);

        User::create([
            'name' => 'Lê Văn Cường',
            'email' => 'user3@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345680',
            'date_of_birth' => now()->subYears(22)->subMonths(7),
            'is_member' => false,
        ]);

        User::create([
            'name' => 'Phạm Thị Dung',
            'email' => 'user4@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345681',
            'date_of_birth' => now()->subYears(28)->subMonths(2),
            'is_member' => true,
        ]);

        User::create([
            'name' => 'Hoàng Văn Em',
            'email' => 'user5@test.com',
            'password' => Hash::make('password'),
            'phone' => '0912345682',
            'date_of_birth' => now()->subYears(35)->subMonths(9),
            'is_member' => false,
        ]);

        // Sample users (generated)
        $names = [
            'Vũ Thị Phương', 'Đặng Văn Giang', 'Bùi Thị Hoa', 'Đỗ Văn Ích', 'Hồ Thị Kim',
            'Ngô Văn Long', 'Dương Thị Mai', 'Lý Văn Nam', 'Trịnh Thị Oanh', 'Phan Văn Phúc',
            'Tôn Thị Quỳnh', 'Võ Văn Rạng', 'Đinh Thị Sương', 'Chu Văn Tùng', 'Cao Thị Uyên',
        ];

        foreach ($names as $index => $name) {
            $email = 'user' . ($index + 6) . '@test.com';
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'phone' => '09' . str_pad($index + 83, 8, '0', STR_PAD_LEFT),
                'date_of_birth' => now()->subYears(rand(18, 50))->subDays(rand(0, 365)),
                'is_member' => rand(0, 1) == 1,
            ]);
        }
    }
}

