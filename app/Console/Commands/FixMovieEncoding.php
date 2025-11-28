<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;

class FixMovieEncoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:fix-encoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix encoding issues for movie titles and descriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra và sửa encoding cho các phim...');
        
        $movies = Movie::all();
        $fixed = 0;
        $skipped = 0;

        foreach ($movies as $movie) {
            $originalTitle = $movie->title;
            $originalDescription = $movie->description;
            
            $fixedTitle = $this->fixEncoding($originalTitle);
            $fixedDescription = $this->fixEncoding($originalDescription);
            
            // Kiểm tra xem có thay đổi không
            if ($fixedTitle !== $originalTitle || $fixedDescription !== $originalDescription) {
                $movie->title = $fixedTitle;
                $movie->description = $fixedDescription;
                $movie->save();
                
                $this->line("✓ Đã sửa: {$originalTitle} → {$fixedTitle}");
                $fixed++;
            } else {
                $skipped++;
            }
        }

        $this->info("\nHoàn thành!");
        $this->info("Đã sửa: {$fixed} phim");
        $this->info("Bỏ qua: {$skipped} phim (không cần sửa)");
        
        return Command::SUCCESS;
    }

    /**
     * Fix encoding for a string
     * Xử lý trường hợp UTF-8 bị hiểu như Windows-1252 (double-encode hoặc triple-encode)
     * Ví dụ: "SƯ THẦY GẶP SƯ LẦY" bị lưu thành "SÆ¯ THáº¦Y Gáº¶P SÆ¯ Láº¦Y"
     * 
     * @param string $text
     * @return string
     */
    private function fixEncoding($text)
    {
        if (empty($text)) {
            return $text;
        }

        $original = $text;
        $maxIterations = 5; // Tối đa 5 lần decode để tránh vòng lặp vô hạn
        
        // Thử decode nhiều lần nếu cần (xử lý double/triple encode)
        for ($i = 0; $i < $maxIterations; $i++) {
            // Kiểm tra xem có chứa ký tự lỗi encoding phổ biến không
            if (str_contains($text, 'Æ') || str_contains($text, 'áº') || str_contains($text, 'á»') || 
                str_contains($text, 'Ã') || str_contains($text, 'Â')) {
                
                // Thử fix bằng cách convert từ Windows-1252 sang UTF-8
                $fixed = @mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
                
                // Nếu kết quả khác và không còn ký tự lỗi, tiếp tục với kết quả mới
                if ($fixed && $fixed !== $text) {
                    // Kiểm tra xem còn ký tự lỗi không
                    if (!str_contains($fixed, 'Æ') && !str_contains($fixed, 'áº') && 
                        !str_contains($fixed, 'á»') && !str_contains($fixed, 'Ã') && 
                        !str_contains($fixed, 'Â')) {
                        return $fixed;
                    }
                    // Nếu vẫn còn lỗi, tiếp tục decode
                    $text = $fixed;
                } else {
                    // Không thể decode thêm, dừng lại
                    break;
                }
            } else {
                // Không còn ký tự lỗi, trả về kết quả
                return $text;
            }
        }

        // Nếu không fix được, trả về nguyên bản
        return $original;
    }
}
