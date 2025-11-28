<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('Đang import phim từ Phim.html...');

        $htmlPath = database_path('seeders/Phim.html');

        if (! file_exists($htmlPath)) {
            $this->command?->error("Không tìm thấy file Phim.html tại: {$htmlPath}");
            return;
        }

        $html = file_get_contents($htmlPath);
        if ($html === false) {
            $this->command?->error('Không đọc được nội dung Phim.html');
            return;
        }

        // Kiểm tra xem file có phải là HTML không (không phải PHP code)
        if (str_starts_with(trim($html), '<?php')) {
            $this->command?->error('File Phim.html không phải là file HTML. File này có vẻ là code PHP.');
            $this->command?->error('Vui lòng đảm bảo file Phim.html chứa nội dung HTML với các thẻ <li class="film-lists">');
            return;
        }

        // Kiểm tra xem có chứa HTML structure không
        if (!str_contains($html, '<li') && !str_contains($html, '<div')) {
            $this->command?->warn('File Phim.html có vẻ không chứa cấu trúc HTML đúng. Đang thử parse...');
        }

        // Fix encoding: đảm bảo HTML được xử lý đúng UTF-8
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        
        // Load HTML với encoding UTF-8 đúng cách
        // Thêm meta charset vào đầu HTML để DOMDocument parse đúng
        $htmlWithCharset = '<?xml encoding="UTF-8">' . $html;
        $dom->loadHTML($htmlWithCharset, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);

        $xpath = new \DOMXPath($dom);
        $movieNodes = $xpath->query('//li[contains(@class,"film-lists")]');

        if (! $movieNodes || $movieNodes->length === 0) {
            $this->command?->error('Không tìm thấy phim nào trong Phim.html (selector li.film-lists).');
            $this->command?->error('Vui lòng kiểm tra:');
            $this->command?->error('1. File Phim.html có chứa các thẻ <li class="film-lists">');
            $this->command?->error('2. File có đúng định dạng HTML');
            $this->command?->error('3. File nằm tại: ' . $htmlPath);
            return;
        }

        $this->command?->info("Tìm thấy {$movieNodes->length} phim trong file HTML.");

        $now = Carbon::now();
        $created = 0;

        foreach ($movieNodes as $index => $li) {
            // Tiêu đề - xử lý encoding UTF-8
            $titleNode = $xpath->query('.//h2[contains(@class,"product-name")]/a', $li)->item(0);
            $title = $titleNode ? trim($titleNode->textContent) : null;
            
            if (! $title) {
                continue;
            }

            // Thể loại - xử lý encoding UTF-8
            $genreNode = $xpath->query('.//div[@class="cgv-movie-info"][span[contains(@class,"cgv-info-bold") and contains(normalize-space(.),"Thể loại")]]/span[contains(@class,"cgv-info-normal")]', $li)->item(0);
            $genre = $genreNode ? trim($genreNode->textContent) : 'Khác';

            // Thời lượng (số phút) - hỗ trợ cả "phút" và "minutes"
            $durationNode = $xpath->query('.//div[@class="cgv-movie-info"][span[contains(@class,"cgv-info-bold") and contains(normalize-space(.),"Thời lượng")]]/span[contains(@class,"cgv-info-normal")]', $li)->item(0);
            $durationText = $durationNode ? trim($durationNode->textContent) : null;
            $duration = 0;
            if ($durationText) {
                // Tìm số đầu tiên trong chuỗi (hỗ trợ cả "94 phút" và "107 minutes")
                if (preg_match('/(\d+)/', $durationText, $m)) {
                    $duration = (int) $m[1];
                }
            }
            if ($duration <= 0) {
                $duration = 100; // default nếu không parse được
            }

            // Ngày khởi chiếu
            $releaseNode = $xpath->query('.//div[@class="cgv-movie-info"][span[contains(@class,"cgv-info-bold") and contains(normalize-space(.),"Khởi chiếu")]]/span[contains(@class,"cgv-info-normal")]', $li)->item(0);
            $releaseText = $releaseNode ? trim($releaseNode->textContent) : null;
            $releaseDate = $now;
            if ($releaseText) {
                $releaseText = trim(preg_replace('/\s+/', ' ', $releaseText));
                try {
                    $releaseDate = Carbon::createFromFormat('d-m-Y', trim($releaseText));
                } catch (\Exception $e) {
                    // Nếu sai format thì giữ mặc định $now
                }
            }

            // Trạng thái: nếu ngày khởi chiếu trong tương lai thì COMING_SOON, ngược lại NOW_SHOWING
            $status = $releaseDate->isFuture() ? 'COMING_SOON' : 'NOW_SHOWING';

            // Poster - tìm nhiều cách để lấy ảnh
            $poster = null;
            
            // Cách 1: Tìm trong div.product-images
            $posterNode = $xpath->query('.//div[contains(@class,"product-images")]//img', $li)->item(0);
            
            // Cách 2: Tìm img có id chứa "product-collection-image"
            if (!$posterNode) {
                $posterNode = $xpath->query('.//img[contains(@id,"product-collection-image")]', $li)->item(0);
            }
            
            // Cách 3: Tìm img đầu tiên trong li
            if (!$posterNode) {
                $posterNode = $xpath->query('.//img', $li)->item(0);
            }
            
            if ($posterNode && $posterNode->hasAttribute('src')) {
                $src = $posterNode->getAttribute('src');
                // Nếu là full URL (bắt đầu bằng http/https) thì giữ nguyên
                // Nếu là relative path (bắt đầu bằng ./) thì bỏ "./"
                if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
                    $poster = $src;
                } else {
                    $poster = ltrim($src, './');
                }
            }
            
            // Log nếu không tìm thấy poster
            if (!$poster) {
                $this->command?->warn("Không tìm thấy poster cho phim: {$title}");
            }

            // Giới hạn độ tuổi từ span.rating (P/C13/C16/C18)
            $ageNode = $xpath->query('.//span[contains(@class,"nmovie-rating")]', $li)->item(0);
            $ageRating = 0;
            if ($ageNode) {
                $ageText = strtoupper(trim($ageNode->textContent));
                if ($ageText === 'P') {
                    $ageRating = 0;
                } elseif (preg_match('/(\d+)/', $ageText, $m)) {
                    $ageRating = (int) $m[1];
                }
            }

            // Mô tả tạm: ghép từ các thông tin có sẵn
            $description = 'Phim "' . $title . '" - thể loại: ' . $genre . ', thời lượng khoảng ' . $duration . ' phút. Dữ liệu được import từ danh sách phim CGV.';

            $endDate = (clone $releaseDate)->addMonths(2);

            Movie::create([
                'title' => $title,
                'description' => $description,
                'poster' => $poster,
                'trailer_url' => null,
                'duration' => $duration,
                'genre' => $genre,
                'country' => 'Various',
                'language' => 'Vietnamese',
                'age_rating' => $ageRating,
                'release_date' => $releaseDate,
                'end_date' => $endDate,
                'status' => $status,
                'rating' => 0,
                'cast' => null,
                'is_featured' => $index < 6, // lấy vài phim đầu làm featured
            ]);

            $created++;
        }

        $this->command?->info("Đã seed {$created} phim từ Phim.html");

        // Tự động tạo lịch chiếu cho các phim
        $this->command?->info("Đang tạo lịch chiếu cho các phim...");
        $this->createShowtimes();
    }

    /**
     * Tạo lịch chiếu tự động cho các phim
     */
    private function createShowtimes(): void
    {
        $rooms = Room::where('is_active', true)->get();
        if ($rooms->isEmpty()) {
            $this->command?->warn('Không có phòng nào active. Vui lòng chạy RoomSeeder trước.');
            return;
        }

        // Các khung giờ chiếu phổ biến
        $timeSlots = [
            '09:00', '11:30', '14:00', '16:30', '19:00', '21:30'
        ];

        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->addDays(14); // Tạo lịch cho 14 ngày tới

        // Tạo lịch cho phim đang chiếu
        $nowShowingMovies = Movie::where('status', 'NOW_SHOWING')->get();
        $showtimesCreated = 0;

        foreach ($nowShowingMovies as $movie) {
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                // Mỗi ngày tạo 3-5 suất chiếu
                $showtimesPerDay = rand(3, 5);
                $selectedRooms = $rooms->random(min($showtimesPerDay, $rooms->count()));

                foreach ($selectedRooms as $room) {
                    // Chọn ngẫu nhiên khung giờ
                    $startTimeStr = $timeSlots[array_rand($timeSlots)];
                    $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $startTimeStr);

                    // Tính end_time = start_time + duration + 15 phút nghỉ
                    $endTime = $startTime->copy()->addMinutes($movie->duration + 15);

                    // Kiểm tra trùng lịch
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
                        $showtimesCreated++;
                    }
                }

                $currentDate->addDay();
            }
        }

        // Tạo lịch cho phim sắp chiếu (bắt đầu từ ngày release)
        $comingSoonMovies = Movie::where('status', 'COMING_SOON')->get();

        foreach ($comingSoonMovies as $movie) {
            $releaseDate = Carbon::parse($movie->release_date)->startOfDay();
            $endShowDate = $releaseDate->copy()->addDays(7); // Tạo lịch cho 7 ngày sau release

            // Chỉ tạo nếu release date trong tương lai
            if ($releaseDate->isFuture()) {
                $currentDate = $releaseDate->copy();

                while ($currentDate <= $endShowDate) {
                    $showtimesPerDay = rand(2, 4); // Ít suất hơn cho phim sắp chiếu
                    $selectedRooms = $rooms->random(min($showtimesPerDay, $rooms->count()));

                    foreach ($selectedRooms as $room) {
                        $startTimeStr = $timeSlots[array_rand($timeSlots)];
                        $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $startTimeStr);
                        $endTime = $startTime->copy()->addMinutes($movie->duration + 15);

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
                            $showtimesCreated++;
                        }
                    }

                    $currentDate->addDay();
                }
            }
        }

        $this->command?->info("Đã tạo {$showtimesCreated} suất chiếu cho các phim.");
    }
}