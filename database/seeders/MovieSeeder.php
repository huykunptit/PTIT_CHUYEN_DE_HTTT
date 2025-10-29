<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Movie::create([
            'title' => 'Avatar: The Way of Water',
            'description' => 'Jake Sully và gia đình của anh ấy khám phá những vùng biển của Pandora.',
            'duration' => 192,
            'genre' => 'Sci-Fi, Action',
            'country' => 'USA',
            'language' => 'English',
            'age_rating' => 13,
            'release_date' => now()->addDays(7),
            'end_date' => now()->addDays(30),
            'status' => 'NOW_SHOWING',
            'rating' => 8.5,
            'is_featured' => true,
        ]);
        
        \App\Models\Movie::create([
            'title' => 'Black Panther: Wakanda Forever',
            'description' => 'Sau cái chết của Vua T\'Challa, Wakanda phải đối mặt với những thách thức mới.',
            'duration' => 161,
            'genre' => 'Action, Adventure',
            'country' => 'USA',
            'language' => 'English',
            'age_rating' => 13,
            'release_date' => now()->addDays(14),
            'end_date' => now()->addDays(45),
            'status' => 'COMING_SOON',
            'rating' => 8.2,
            'is_featured' => true,
        ]);
        
        \App\Models\Movie::create([
            'title' => 'Top Gun: Maverick',
            'description' => 'Pete "Maverick" Mitchell trở lại với nhiệm vụ nguy hiểm nhất trong sự nghiệp.',
            'duration' => 131,
            'genre' => 'Action, Drama',
            'country' => 'USA',
            'language' => 'English',
            'age_rating' => 13,
            'release_date' => now()->subDays(30),
            'end_date' => now()->addDays(15),
            'status' => 'NOW_SHOWING',
            'rating' => 8.8,
            'is_featured' => false,
        ]);
    }
}
