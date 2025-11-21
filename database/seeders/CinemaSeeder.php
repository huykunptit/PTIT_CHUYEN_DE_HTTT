<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cinema;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cinemas = [
            // TP.HCM
            [
                'name' => 'CGV Landmark 81',
                'address' => 'Tầng 3, Landmark 81, Vinhomes Central Park, Q. Bình Thạnh, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 7300 1881',
                'email' => 'landmark81@cgv.com',
                'description' => 'Rạp chiếu phim hiện đại với công nghệ IMAX và 4DX',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Vincom Center',
                'address' => 'Tầng 4, Vincom Center, 72 Lê Thánh Tôn, Q.1, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3822 8899',
                'email' => 'vincom@cgv.com',
                'description' => 'Rạp chiếu phim tại trung tâm thành phố',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Crescent Mall',
                'address' => 'Tầng 5, Crescent Mall, 101 Tôn Dật Tiên, Q.7, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 5413 5588',
                'email' => 'crescent@cgv.com',
                'description' => 'Rạp chiếu phim với hệ thống âm thanh Dolby Atmos',
                'is_active' => true,
            ],
            [
                'name' => 'Lotte Cinema Diamond',
                'address' => 'Tầng 13, Lotte Center, 469 Nguyễn Hữu Thọ, Q.7, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3775 7777',
                'email' => 'diamond@lottecinema.com',
                'description' => 'Rạp chiếu phim cao cấp với ghế VIP',
                'is_active' => true,
            ],
            [
                'name' => 'Galaxy Cinema Nguyễn Du',
                'address' => 'Tầng 3-4, 116 Nguyễn Du, Q.1, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3827 2603',
                'email' => 'nguyendu@galaxycinema.com',
                'description' => 'Rạp chiếu phim tại trung tâm quận 1',
                'is_active' => true,
            ],
            [
                'name' => 'BHD Star Cineplex Bitexco',
                'address' => 'Tầng 5, Bitexco Financial Tower, 2 Hải Triều, Q.1, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3914 6666',
                'email' => 'bitexco@bhdstar.com',
                'description' => 'Rạp chiếu phim view đẹp tại tòa nhà cao nhất Sài Gòn',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Saigon Centre',
                'address' => 'Tầng 3, Saigon Centre, 65 Lê Lợi, Q.1, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3829 2233',
                'email' => 'saigoncentre@cgv.com',
                'description' => 'Rạp chiếu phim hiện đại với công nghệ 3D',
                'is_active' => true,
            ],
            [
                'name' => 'Lotte Cinema Cantavil',
                'address' => 'Tầng 4, Cantavil, Số 1 Song Hành, Q.2, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3740 9999',
                'email' => 'cantavil@lottecinema.com',
                'description' => 'Rạp chiếu phim tại quận 2',
                'is_active' => true,
            ],
            [
                'name' => 'CGV AEON Tân Phú',
                'address' => 'Tầng 3, AEON Mall Tân Phú, 30 Bờ Bao Tân Thắng, Q.Tân Phú, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3862 3355',
                'email' => 'aeontanphu@cgv.com',
                'description' => 'Rạp chiếu phim tại trung tâm thương mại AEON',
                'is_active' => true,
            ],
            [
                'name' => 'Mega GS Cinemas',
                'address' => 'Tầng 5, Mega GS, 123 Nguyễn Trãi, Q.5, TP.HCM',
                'city' => 'HCM',
                'phone' => '028 3835 8888',
                'email' => 'mega@gs.com',
                'description' => 'Rạp chiếu phim với giá vé ưu đãi',
                'is_active' => true,
            ],
            // Hà Nội
            [
                'name' => 'CGV Vincom Royal City',
                'address' => 'B2 - R3, Vincom Mega Mall Royal City, 72A Nguyễn Trãi, Thanh Xuân, Hà Nội',
                'city' => 'HN',
                'phone' => '024 7304 6789',
                'email' => 'royalcity@cgv.com',
                'description' => 'Rạp chiếu phim hiện đại tại khu đô thị Royal City',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Vincom Times City',
                'address' => 'Tầng 5, TTTM Vincom Times City, 458 Minh Khai, Hai Bà Trưng, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3206 8686',
                'email' => 'timescity@cgv.com',
                'description' => 'Tổ hợp rạp chiếu phim với nhiều phòng IMAX, 4DX',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Vincom Bà Triệu',
                'address' => 'Tầng 7, Vincom Bà Triệu, 191 Bà Triệu, Hai Bà Trưng, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3974 9999',
                'email' => 'batrieu@cgv.com',
                'description' => 'Một trong những rạp CGV đầu tiên tại Hà Nội',
                'is_active' => true,
            ],
            [
                'name' => 'CGV Vincom Nguyễn Chí Thanh',
                'address' => 'Tầng 6, Vincom Center Nguyễn Chí Thanh, 54A Nguyễn Chí Thanh, Đống Đa, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3228 6868',
                'email' => 'nguyenchithanh@cgv.com',
                'description' => 'Rạp chiếu phim với nhiều phòng chiếu VIP',
                'is_active' => true,
            ],
            [
                'name' => 'Lotte Cinema Keangnam',
                'address' => 'Tầng 5, Keangnam Landmark 72, Phạm Hùng, Nam Từ Liêm, Hà Nội',
                'city' => 'HN',
                'phone' => '024 6282 5555',
                'email' => 'keangnam@lottecinema.com',
                'description' => 'Rạp chiếu phim cao cấp tại tòa nhà Landmark 72',
                'is_active' => true,
            ],
            [
                'name' => 'Lotte Cinema Hà Đông',
                'address' => 'Tầng 4, TTTM Mê Linh Plaza, Hà Đông, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3311 8999',
                'email' => 'hadong@lottecinema.com',
                'description' => 'Rạp Lotte phục vụ khu vực Hà Đông',
                'is_active' => true,
            ],
            [
                'name' => 'Beta Cinemas Mỹ Đình',
                'address' => 'Tầng 3, Golden Field, 24 Nguyễn Cơ Thạch, Nam Từ Liêm, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3202 3888',
                'email' => 'mydinh@betacinemas.vn',
                'description' => 'Rạp chiếu phim giá rẻ, phù hợp sinh viên',
                'is_active' => true,
            ],
            [
                'name' => 'Beta Cinemas Thanh Xuân',
                'address' => 'Tầng 3, TTTM Stellar Garden, 35 Lê Văn Thiêm, Thanh Xuân, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3205 9999',
                'email' => 'thanhxuan@betacinemas.vn',
                'description' => 'Rạp Beta mới với nhiều ưu đãi',
                'is_active' => true,
            ],
            [
                'name' => 'BHD Star Discovery Complex',
                'address' => 'Tầng 8-9, Discovery Complex, 302 Cầu Giấy, Hà Nội',
                'city' => 'HN',
                'phone' => '024 2242 2222',
                'email' => 'discovery@bhdstar.com',
                'description' => 'Rạp chiếu phim với nhiều phòng chiếu hiện đại',
                'is_active' => true,
            ],
            [
                'name' => 'BHD Star Vincom Phạm Ngọc Thạch',
                'address' => 'Tầng 4, Vincom Center Phạm Ngọc Thạch, Đống Đa, Hà Nội',
                'city' => 'HN',
                'phone' => '024 6263 6868',
                'email' => 'pngocthach@bhdstar.com',
                'description' => 'Rạp BHD Star tại khu vực trung tâm Đống Đa',
                'is_active' => true,
            ],
            [
                'name' => 'Galaxy Mipec Long Biên',
                'address' => 'Tầng 3, Mipec Long Biên, Ngõ 2 Long Biên 2, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3699 4888',
                'email' => 'longbien@galaxycine.vn',
                'description' => 'Rạp Galaxy đầu tiên tại Hà Nội, âm thanh Dolby Atmos',
                'is_active' => true,
            ],
            [
                'name' => 'Trung tâm Chiếu phim Quốc gia',
                'address' => '87 Láng Hạ, Ba Đình, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3514 2278',
                'email' => 'info@chieuphimquocgia.com.vn',
                'description' => 'Rạp nhà nước, nhiều sự kiện điện ảnh lớn',
                'is_active' => true,
            ],
            [
                'name' => 'Cinestar Hai Bà Trưng',
                'address' => '135 Minh Khai, Hai Bà Trưng, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3633 3399',
                'email' => 'hbt@cinestar.com.vn',
                'description' => 'Cinestar với giá vé hợp lý tại trung tâm Hà Nội',
                'is_active' => true,
            ],
            [
                'name' => 'Starlight Long Biên',
                'address' => 'Tầng 5, Savico Megamall, 7-9 Nguyễn Văn Linh, Long Biên, Hà Nội',
                'city' => 'HN',
                'phone' => '024 3655 6868',
                'email' => 'longbien@starlight.vn',
                'description' => 'Tổ hợp giải trí Starlight với nhiều phòng chiếu',
                'is_active' => true,
            ],
        ];

        foreach ($cinemas as $cinema) {
            Cinema::create($cinema);
        }
    }
}

