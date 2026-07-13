<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@unguspa.com'))->firstOrFail();

        $articles = [
            ['Manfaat Massage untuk Membantu Tubuh Lebih Rileks', 'manfaat massage', 'Massage dapat menjadi pilihan perawatan untuk membantu meredakan ketegangan dan membuat tubuh terasa lebih nyaman.', 'packages_tradisional.webp'],
            ['Cara Memilih Layanan Spa Panggilan yang Tepat', 'spa panggilan', 'Kenali beberapa hal penting sebelum memilih layanan spa panggilan agar perawatan terasa nyaman dan sesuai kebutuhan.', 'heroo.webp'],
            ['Perbedaan Massage Tradisional dan Refleksologi', 'massage tradisional dan refleksologi', 'Massage tradisional dan refleksologi memiliki fokus berbeda. Pelajari perbedaannya sebelum menentukan treatment.', 'packages_Reflextologi.webp'],
            ['Waktu Terbaik Menikmati Massage Setelah Beraktivitas', 'waktu terbaik massage', 'Menentukan waktu massage yang tepat membantu Anda menikmati sesi relaksasi dengan lebih nyaman dan maksimal.', 'about-us.webp'],
            ['Manfaat Lulur untuk Menjaga Kesegaran Kulit', 'manfaat lulur', 'Lulur merupakan perawatan tubuh yang membantu membersihkan kulit dan membuatnya terasa lebih halus serta segar.', 'packages_pijatdanlulur.webp'],
            ['Persiapan Sebelum Terapis Spa Datang ke Rumah', 'persiapan spa di rumah', 'Persiapkan ruangan, waktu, dan kebutuhan sederhana ini agar layanan spa panggilan di rumah berjalan lebih nyaman.', 'image fantasy massage 4-3.webp'],
            ['Tips Menjaga Tubuh Tetap Nyaman Setelah Massage', 'perawatan setelah massage', 'Beberapa kebiasaan sederhana setelah massage dapat membantu mempertahankan rasa nyaman dan rileks pada tubuh.', 'packages_tradisional.webp'],
            ['Mengenal Refleksologi dan Titik Refleksi Kaki', 'refleksologi kaki', 'Refleksologi berfokus pada area kaki dengan teknik tekanan tertentu untuk memberikan pengalaman relaksasi.', 'packages_Reflextologi.webp'],
            ['Spa dan Kerokan: Perawatan Hangat Setelah Hari yang Sibuk', 'spa dan kerokan', 'Kombinasi spa dan kerokan memberikan sensasi hangat yang cocok dinikmati setelah aktivitas yang melelahkan.', 'packages_pijatdanrokaan.webp'],
            ['Panduan Booking Spa Panggilan dengan Aman dan Nyaman', 'booking spa panggilan', 'Ikuti panduan sederhana untuk memesan layanan spa panggilan, mulai dari memilih treatment sampai konfirmasi jadwal.', 'heroo-bg.webp'],
        ];

        foreach ($articles as $index => [$title, $keyword, $excerpt, $image]) {
            $slug = Str::slug($title);
            $publishedAt = now()->subDays($index + 1)->setTime(9, 0);

            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $admin->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $this->content($title, $keyword),
                    'featured_image' => 'assets/ganbar/'.$image,
                    'image_alt' => $slug.'-gambar',
                    'status' => 'published',
                    'published_at' => $publishedAt,
                    'meta_title' => Str::limit($title.' | Ungu Spa', 60, ''),
                    'meta_description' => Str::limit($excerpt, 155, ''),
                    'focus_keyword' => $keyword,
                    'canonical_url' => null,
                    'robots_index' => true,
                    'robots_follow' => true,
                    'og_title' => $title.' | Ungu Spa',
                    'og_description' => $excerpt,
                    'og_image' => null,
                ]
            );
        }
    }

    private function content(string $title, string $keyword): string
    {
        return <<<HTML
<p>{$title} dapat menjadi informasi penting bagi Anda yang ingin menikmati waktu istirahat dengan lebih berkualitas. Setiap orang memiliki kebutuhan tubuh yang berbeda, sehingga treatment sebaiknya dipilih berdasarkan kondisi dan kenyamanan pribadi.</p>
<h2>Mengapa {$keyword} perlu dipahami?</h2>
<p>Memahami jenis perawatan membantu Anda menentukan layanan yang sesuai. Komunikasikan area tubuh yang terasa kurang nyaman, durasi yang diinginkan, dan tingkat tekanan kepada terapis sebelum sesi dimulai.</p>
<h2>Tips sebelum memulai perawatan</h2>
<ul><li>Pilih waktu ketika aktivitas utama sudah selesai.</li><li>Siapkan tempat yang bersih, tenang, dan memiliki ruang yang cukup.</li><li>Berikan informasi kondisi tubuh secara jujur kepada terapis.</li><li>Minum air putih secukupnya dan gunakan pakaian yang nyaman.</li></ul>
<h2>Nikmati layanan sesuai kebutuhan Anda</h2>
<p>Ungu Spa menyediakan layanan massage dan spa panggilan dengan jadwal fleksibel. Hubungi admin untuk berkonsultasi mengenai pilihan treatment dan waktu kedatangan terapis.</p>
HTML;
    }
}
