<?php

namespace Database\Seeders;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Modules\PageBuilder\app\Models\CustomPage;
use Modules\Language\app\Enums\TranslationModels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class CustomPageSeeder extends Seeder
{
    use GenerateTranslationTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $request = new Request([
            'name' => 'privacy policy',
            'content' => "<p>Selamat datang di LMS Kabupaten Bantul! Kami menghargai privasi Anda dan berkomitmen untuk melindungi informasi pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi data Anda ketika Anda mengunjungi situs web kami.<p><strong>Informasi yang Kami Kumpulkan</strong></p><ol><li><p><strong>Informasi Pribadi</strong>: Ketika Anda mendaftar di situs kami, berlangganan newsletter, atau mengisi formulir, kami mungkin mengumpulkan informasi pribadi seperti nama, alamat email, nomor telepon, dan detail lainnya yang Anda berikan.</p></li><li><p><strong>Informasi Non-Pribadi</strong>: Kami mungkin mengumpulkan informasi non-pribadi tentang pengunjung ketika mereka berinteraksi dengan situs kami. Ini mungkin termasuk nama browser, jenis komputer, dan informasi teknis tentang cara pengguna terhubung ke situs kami, seperti sistem operasi dan penyedia layanan internet yang digunakan, dan informasi serupa lainnya.</p></li><li><p><strong>Cookies dan Teknologi Pelacakan</strong>: Situs kami mungkin menggunakan 'cookies' untuk meningkatkan pengalaman pengguna. Browser web pengguna menempatkan cookies di hard drive mereka untuk keperluan pencatatan dan terkadang untuk melacak informasi tentang mereka. Pengguna dapat memilih untuk mengatur browser web mereka untuk menolak cookies atau memberi tahu mereka ketika cookies sedang dikirim. Jika mereka melakukannya, perlu diingat bahwa beberapa bagian situs mungkin tidak berfungsi dengan benar.</p></li></ol><p><strong>Cara Kami Menggunakan Informasi yang Dikumpulkan</strong></p><p>LMS Kabupaten Bantul dapat mengumpulkan dan menggunakan informasi pribadi Anda untuk tujuan-tujuan berikut:</p><ol><li><p><strong>Untuk Meningkatkan Layanan Pelanggan</strong>: Informasi yang Anda berikan membantu kami merespons kebutuhan layanan pelanggan dan dukungan Anda dengan lebih efisien.</p></li><li><p><strong>Untuk Memersonalisasi Pengalaman Pengguna</strong>: Kami mungkin menggunakan informasi agregat untuk memahami bagaimana pengguna kami sebagai kelompok menggunakan layanan dan sumber daya yang disediakan di situs kami.</p></li><li><p><strong>Untuk Meningkatkan Situs Kami</strong>: Kami mungkin menggunakan umpan balik yang Anda berikan untuk meningkatkan produk dan layanan kami.</p></li><li><p><strong>Untuk Memproses Pembayaran</strong>: Kami mungkin menggunakan informasi yang Anda berikan tentang diri Anda ketika melakukan pemesanan hanya untuk memberikan layanan pada pemesanan tersebut. Kami tidak membagikan informasi ini dengan pihak luar kecuali sejauh yang diperlukan untuk memberikan layanan.</p></li><li><p><strong>Untuk Mengirim Email Berkala</strong>: Kami mungkin menggunakan alamat email Anda untuk mengirimkan informasi dan pembaruan tentang produk kami. Kami juga mungkin menggunakan alamat email Anda untuk menanggapi pertanyaan, komentar, dan/atau permintaan lainnya.</p></li></ol><p><strong>Cara Kami Melindungi Informasi Anda</strong></p><p>Kami mengadopsi praktik pengumpulan, penyimpanan, dan pengolahan data yang tepat dan mengadopsi tindakan keamanan yang sesuai untuk melindungi informasi pribadi Anda, nama pengguna, kata sandi, informasi transaksi, dan data yang disimpan di situs kami terhadap akses yang tidak sah, perubahan, pengungkapan, atau penghancuran.</p>",
        ]);

        $pageBuilder = CustomPage::create([
            'slug' => 'privacy-policy',
            'status' => 1,
        ]);

        $this->generateTranslations(
            TranslationModels::CustomPage,
            $pageBuilder,
            'custom_page_id',
            $request,
        );


        $request = new Request([
            'name' => 'terms and conditions',
            'content' => "<p>Harap baca Syarat dan Kondisi ini.<br>Akses Anda ke dan penggunaan Layanan kami diatur oleh penerimaan dan kepatuhan Anda terhadap Syarat ini. Syarat ini berlaku untuk semua pengunjung, pengguna, dan orang lain yang mengakses atau menggunakan Layanan.<br>Dengan mengakses atau menggunakan Layanan, Anda setuju untuk terikat oleh Syarat ini. Jika Anda tidak setuju dengan bagian mana pun dari syarat maka Anda tidak dapat mengakses Layanan.</p><p><strong>Akun</strong></p><p>Ketika Anda membuat akun dengan kami, Anda harus memberikan informasi yang akurat, lengkap, dan mutakhir pada setiap saat. Kegagalan untuk melakukan hal tersebut merupakan pelanggaran Syarat, yang dapat mengakibatkan penghentian akun Anda di Layanan kami.</p><p>Anda bertanggung jawab untuk menjaga kata sandi yang Anda gunakan untuk mengakses Layanan dan untuk semua aktivitas atau tindakan di bawah kata sandi Anda, baik itu dengan Layanan kami atau layanan pihak ketiga.</p><p>Anda setuju untuk tidak mengungkapkan kata sandi Anda kepada pihak ketiga mana pun. Anda harus memberitahu kami segera setelah Anda sadar akan adanya pelanggaran keamanan atau penggunaan akun yang tidak sah.</p><p><strong>Pranala ke Situs Web Lain</strong></p><p>Layanan kami dapat berisi pranala ke situs web atau layanan pihak ketiga yang tidak dimiliki atau dioperasikan oleh LMS Kabupaten Bantul.</p><p>LMS Kabupaten Bantul tidak memiliki kontrol atas, dan tidak bertanggung jawab atas, isi, kebijakan privasi, atau praktik situs web atau layanan pihak ketiga mana pun. Anda juga mengakui dan setuju bahwa LMS Kabupaten Bantul tidak akan bertanggung jawab atau memiliki kewajiban, baik secara langsung maupun tidak langsung, atas setiap kerusakan atau kehilangan yang diakibatkan oleh atau dalam kaitannya dengan penggunaan atau kepercayaan pada setiap isi, barang, atau layanan yang tersedia pada atau melalui situs web atau layanan mana pun.</p><p>Kami sangat menyarankan Anda untuk membaca syarat dan kebijakan privasi setiap situs web atau layanan pihak ketiga yang Anda kunjungi.</p><p><strong>Penghentian</strong></p><p>Kami dapat menghentikan atau menangguhkan akses ke Layanan kami setiap saat, tanpa pemberitahuan sebelumnya atau kewajiban, atas alasan apa pun, termasuk tanpa batasan jika Anda melanggar Syarat.</p><p>Segala ketentuan Syarat yang sifatnya harus bertahan setelah penghentian akan bertahan setelah penghentian, termasuk tanpa batasan, ketentuan kepemilikan, penyangkalan garansi, ganti rugi, dan batasan kewajiban.</p><p>Kami dapat menghentikan atau menangguhkan akun Anda setiap saat, tanpa pemberitahuan sebelumnya atau kewajiban, atas alasan apa pun, termasuk tanpa batasan jika Anda melanggar Syarat.</p><p>Setelah penghentian, hak Anda untuk menggunakan Layanan akan segera berakhir. Jika Anda ingin menghentikan akun Anda, Anda dapat dengan mudah berhenti menggunakan Layanan.</p>",
        ]);

        $pageBuilder = CustomPage::create([
            'slug' => 'terms-and-conditions',
            'status' => 1,
        ]);

        $this->generateTranslations(
            TranslationModels::CustomPage,
            $pageBuilder,
            'custom_page_id',
            $request,
        );
    }
}
