<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = [
            // Phim đang chiếu (NOW_SHOWING)
            ['title' => 'Avatar: The Way of Water', 'description' => 'Jake Sully và gia đình khám phá những vùng biển của Pandora, gặp gỡ các sinh vật biển kỳ lạ.', 'duration' => 192, 'genre' => 'Sci-Fi, Action', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 8.5, 'is_featured' => true],
            ['title' => 'Top Gun: Maverick', 'description' => 'Pete "Maverick" Mitchell trở lại với nhiệm vụ nguy hiểm nhất trong sự nghiệp của mình.', 'duration' => 131, 'genre' => 'Action, Drama', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 8.8, 'is_featured' => true],
            ['title' => 'Spider-Man: No Way Home', 'description' => 'Peter Parker phải đối mặt với hậu quả khi danh tính của mình bị tiết lộ.', 'duration' => 148, 'genre' => 'Action, Adventure', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 8.7, 'is_featured' => false],
            ['title' => 'The Batman', 'description' => 'Batman điều tra các vụ án bí ẩn tại Gotham City và đối đầu với Riddler.', 'duration' => 176, 'genre' => 'Action, Crime', 'country' => 'USA', 'language' => 'English', 'age_rating' => 16, 'status' => 'NOW_SHOWING', 'rating' => 8.3, 'is_featured' => false],
            ['title' => 'Doctor Strange in the Multiverse of Madness', 'description' => 'Doctor Strange khám phá đa vũ trụ và gặp gỡ các phiên bản khác của chính mình.', 'duration' => 126, 'genre' => 'Action, Fantasy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.5, 'is_featured' => false],
            ['title' => 'Jurassic World Dominion', 'description' => 'Loài khủng long sinh sống cùng con người trên toàn thế giới.', 'duration' => 146, 'genre' => 'Action, Adventure', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 6.8, 'is_featured' => false],
            ['title' => 'Thor: Love and Thunder', 'description' => 'Thor tham gia vào cuộc hành trình không giống bất kỳ điều gì anh từng đối mặt.', 'duration' => 118, 'genre' => 'Action, Comedy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.2, 'is_featured' => false],
            ['title' => 'Minions: The Rise of Gru', 'description' => 'Cuộc phiêu lưu của nhóm Minions trẻ tuổi khi gặp Gru lần đầu tiên.', 'duration' => 87, 'genre' => 'Animation, Comedy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 0, 'status' => 'NOW_SHOWING', 'rating' => 7.1, 'is_featured' => false],
            ['title' => 'Lightyear', 'description' => 'Câu chuyện về nguồn gốc của Buzz Lightyear, người hùng không gian.', 'duration' => 105, 'genre' => 'Animation, Adventure', 'country' => 'USA', 'language' => 'English', 'age_rating' => 0, 'status' => 'NOW_SHOWING', 'rating' => 6.5, 'is_featured' => false],
            ['title' => 'Elvis', 'description' => 'Cuộc đời và sự nghiệp của ca sĩ Elvis Presley.', 'duration' => 159, 'genre' => 'Biography, Drama', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.8, 'is_featured' => false],
            
            // Phim Việt Nam
            ['title' => 'Bố Già', 'description' => 'Câu chuyện cảm động về tình cha con trong gia đình.', 'duration' => 128, 'genre' => 'Drama, Family', 'country' => 'Vietnam', 'language' => 'Vietnamese', 'age_rating' => 0, 'status' => 'NOW_SHOWING', 'rating' => 8.2, 'is_featured' => true],
            ['title' => 'Em Chưa 18', 'description' => 'Câu chuyện về tuổi trẻ và tình yêu học trò.', 'duration' => 105, 'genre' => 'Romance, Drama', 'country' => 'Vietnam', 'language' => 'Vietnamese', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.5, 'is_featured' => false],
            ['title' => 'Đất Rừng Phương Nam', 'description' => 'Cuộc sống và chiến đấu của người dân Nam Bộ thời kháng chiến.', 'duration' => 140, 'genre' => 'Drama, War', 'country' => 'Vietnam', 'language' => 'Vietnamese', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 8.0, 'is_featured' => false],
            ['title' => '578: Phát Đạn Của Kẻ Điên', 'description' => 'Hành trình trả thù của một người cha sau khi con gái bị sát hại.', 'duration' => 118, 'genre' => 'Action, Thriller', 'country' => 'Vietnam', 'language' => 'Vietnamese', 'age_rating' => 18, 'status' => 'NOW_SHOWING', 'rating' => 7.3, 'is_featured' => false],
            ['title' => 'Trạng Tí Phiêu Lưu Ký', 'description' => 'Cuộc phiêu lưu của Trạng Tí qua các vùng đất khác nhau.', 'duration' => 95, 'genre' => 'Animation, Adventure', 'country' => 'Vietnam', 'language' => 'Vietnamese', 'age_rating' => 0, 'status' => 'NOW_SHOWING', 'rating' => 7.0, 'is_featured' => false],
            
            // Phim Hàn Quốc
            ['title' => 'Parasite', 'description' => 'Gia đình nghèo khó tìm cách xâm nhập vào gia đình giàu có.', 'duration' => 132, 'genre' => 'Thriller, Drama', 'country' => 'South Korea', 'language' => 'Korean', 'age_rating' => 16, 'status' => 'NOW_SHOWING', 'rating' => 9.2, 'is_featured' => true],
            ['title' => 'Train to Busan', 'description' => 'Cuộc chiến sinh tồn trên chuyến tàu từ Seoul đến Busan.', 'duration' => 118, 'genre' => 'Action, Horror', 'country' => 'South Korea', 'language' => 'Korean', 'age_rating' => 16, 'status' => 'NOW_SHOWING', 'rating' => 8.6, 'is_featured' => false],
            ['title' => 'The Handmaiden', 'description' => 'Câu chuyện về nữ hầu gái và người phụ nữ quý tộc.', 'duration' => 145, 'genre' => 'Drama, Romance', 'country' => 'South Korea', 'language' => 'Korean', 'age_rating' => 18, 'status' => 'NOW_SHOWING', 'rating' => 8.4, 'is_featured' => false],
            
            // Phim hành động
            ['title' => 'John Wick: Chapter 4', 'description' => 'John Wick tiếp tục cuộc chiến với High Table.', 'duration' => 169, 'genre' => 'Action, Crime', 'country' => 'USA', 'language' => 'English', 'age_rating' => 18, 'status' => 'NOW_SHOWING', 'rating' => 8.5, 'is_featured' => false],
            ['title' => 'Fast X', 'description' => 'Dom và gia đình đối mặt với kẻ thù nguy hiểm nhất.', 'duration' => 141, 'genre' => 'Action, Crime', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.0, 'is_featured' => false],
            ['title' => 'Mission: Impossible - Dead Reckoning', 'description' => 'Ethan Hunt và IMF đối mặt với mối đe dọa mới.', 'duration' => 163, 'genre' => 'Action, Thriller', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 8.3, 'is_featured' => true],
            ['title' => 'Black Adam', 'description' => 'Black Adam được giải phóng khỏi ngôi mộ cổ.', 'duration' => 125, 'genre' => 'Action, Fantasy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 6.9, 'is_featured' => false],
            
            // Phim kinh dị
            ['title' => 'Smile', 'description' => 'Bác sĩ tâm thần đối mặt với nỗi ám ảnh kỳ lạ.', 'duration' => 115, 'genre' => 'Horror, Thriller', 'country' => 'USA', 'language' => 'English', 'age_rating' => 18, 'status' => 'NOW_SHOWING', 'rating' => 7.2, 'is_featured' => false],
            ['title' => 'Nope', 'description' => 'Hai anh em phát hiện ra điều bất thường trên bầu trời.', 'duration' => 130, 'genre' => 'Horror, Sci-Fi', 'country' => 'USA', 'language' => 'English', 'age_rating' => 16, 'status' => 'NOW_SHOWING', 'rating' => 7.8, 'is_featured' => false],
            ['title' => 'The Black Phone', 'description' => 'Cậu bé bị bắt cóc và nhận được cuộc gọi từ những nạn nhân trước đó.', 'duration' => 103, 'genre' => 'Horror, Thriller', 'country' => 'USA', 'language' => 'English', 'age_rating' => 18, 'status' => 'NOW_SHOWING', 'rating' => 7.5, 'is_featured' => false],
            
            // Phim tình cảm
            ['title' => 'Ticket to Paradise', 'description' => 'Cặp vợ chồng ly dị hợp tác để ngăn con gái kết hôn.', 'duration' => 104, 'genre' => 'Romance, Comedy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 7.3, 'is_featured' => false],
            ['title' => 'Everything Everywhere All at Once', 'description' => 'Người phụ nữ phải cứu đa vũ trụ bằng cách kết nối với các phiên bản của mình.', 'duration' => 139, 'genre' => 'Action, Comedy', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'NOW_SHOWING', 'rating' => 9.0, 'is_featured' => true],
            
            // Phim sắp chiếu
            ['title' => 'Black Panther: Wakanda Forever', 'description' => 'Wakanda đối mặt với những thách thức mới sau cái chết của Vua T\'Challa.', 'duration' => 161, 'genre' => 'Action, Adventure', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'COMING_SOON', 'rating' => 8.2, 'is_featured' => true],
            ['title' => 'Avatar 3', 'description' => 'Tiếp tục cuộc phiêu lưu của Jake Sully trên Pandora.', 'duration' => 180, 'genre' => 'Sci-Fi, Action', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'COMING_SOON', 'rating' => 0, 'is_featured' => true],
            ['title' => 'Guardians of the Galaxy Vol. 3', 'description' => 'Nhóm Guardians tiếp tục cuộc phiêu lưu vũ trụ.', 'duration' => 150, 'genre' => 'Action, Adventure', 'country' => 'USA', 'language' => 'English', 'age_rating' => 13, 'status' => 'COMING_SOON', 'rating' => 0, 'is_featured' => false],
        ];

        foreach ($movies as $index => $movieData) {
            $releaseDate = $movieData['status'] === 'NOW_SHOWING' 
                ? now()->subDays(rand(1, 60))
                : now()->addDays(rand(1, 90));
            
            $endDate = $releaseDate->copy()->addDays(rand(30, 90));
            
            Movie::create(array_merge($movieData, [
                'release_date' => $releaseDate,
                'end_date' => $endDate,
            ]));
        }

        // Thêm phim ngẫu nhiên để đủ 50 phim
        $genres = ['Action', 'Comedy', 'Drama', 'Romance', 'Thriller', 'Horror', 'Sci-Fi', 'Fantasy', 'Adventure', 'Animation'];
        $countries = ['USA', 'Vietnam', 'South Korea', 'China', 'Japan', 'India', 'France', 'UK'];
        $languages = ['English', 'Vietnamese', 'Korean', 'Chinese', 'Japanese', 'Hindi', 'French'];
        
        for ($i = count($movies); $i < 50; $i++) {
            $genre = $genres[array_rand($genres)] . ', ' . $genres[array_rand($genres)];
            $country = $countries[array_rand($countries)];
            $language = $languages[array_rand($languages)];
            $status = rand(0, 1) == 0 ? 'NOW_SHOWING' : 'COMING_SOON';
            
            $releaseDate = $status === 'NOW_SHOWING' 
                ? now()->subDays(rand(1, 60))
                : now()->addDays(rand(1, 90));
            
            Movie::create([
                'title' => 'Movie ' . ($i + 1),
                'description' => 'Mô tả phim ' . ($i + 1) . ': Câu chuyện hấp dẫn về...',
                'duration' => rand(90, 180),
                'genre' => $genre,
                'country' => $country,
                'language' => $language,
                'age_rating' => [0, 13, 16, 18][array_rand([0, 13, 16, 18])],
                'release_date' => $releaseDate,
                'end_date' => $releaseDate->copy()->addDays(rand(30, 90)),
                'status' => $status,
                'rating' => round(rand(50, 95) / 10, 1),
                'is_featured' => rand(0, 4) == 0, // 20% featured
        ]);
    }
}
}

