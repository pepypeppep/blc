<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Course\app\Models\CourseTos;

class TosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseTos::updateOrCreate([
            'course_id' => 1
        ], [
            'description' => '<p class="ds-markdown-paragraph"><strong>SISTEM MANAJEMEN PEMBELAJARAN (LEARNING MANAGEMENT SYSTEM - LMS)</strong></p>
<p class="ds-markdown-paragraph"><strong>Terakhir diperbarui: [Tanggal/Bulan/Tahun]</strong></p>
<h3><strong>1. Pengenalan</strong></h3>
<p class="ds-markdown-paragraph">Syarat dan Ketentuan Layanan ("<strong>Syarat</strong>") ini mengatur penggunaan platform <strong>Learning Management System (LMS)</strong> ("<strong>Platform</strong>") yang disediakan oleh <strong>[Nama Penyedia LMS]</strong> ("<strong>Kami</strong>"). Dengan mengakses atau menggunakan Platform ini, Anda ("<strong>Pengguna</strong>") menyetujui untuk terikat oleh Syarat ini. Jika tidak setuju, harap hentikan penggunaan Platform.</p>
<h3><strong>2. Definisi</strong></h3>
<ul>
<li>
<p class="ds-markdown-paragraph"><strong>LMS</strong>: Sistem daring untuk mengelola, mendistribusi, dan memantau materi pembelajaran.</p>
</li>
<li>
<p class="ds-markdown-paragraph"><strong>Pengguna</strong>: Individu atau institusi yang terdaftar dan menggunakan Platform, termasuk siswa, pengajar, atau administrator.</p>
</li>
<li>
<p class="ds-markdown-paragraph"><strong>Konten</strong>: Materi pembelajaran, dokumen, video, tugas, atau informasi lain yang diunggah ke Platform.</p>
</li>
</ul>
<h3><strong>3. Registrasi dan Akun</strong></h3>
<p class="ds-markdown-paragraph">3.1. Pengguna harus mendaftar dengan informasi akurat dan menjaga kerahasiaan kata sandi.<br>3.2. Pengguna bertanggung jawab penuh atas aktivitas yang dilakukan melalui akunnya.<br>3.3. Kami berhak menangguhkan akun jika ditemukan pelanggaran atau ketidaksesuaian data.</p>
<h3><strong>4. Hak dan Kewajiban Pengguna</strong></h3>
<p class="ds-markdown-paragraph">4.1. <strong>Hak Pengguna</strong>:</p>
<ul>
<li>
<p class="ds-markdown-paragraph">Mengakses materi pembelajaran sesuai izin yang diberikan.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Berpartisipasi dalam aktivitas pembelajaran (diskusi, ujian, dll.).</p>
</li>
</ul>
<p class="ds-markdown-paragraph">4.2. <strong>Kewajiban Pengguna</strong>:</p>
<ul>
<li>
<p class="ds-markdown-paragraph">Tidak menyalahgunakan Platform untuk tindakan ilegal, plagiarisme, atau pelanggaran hak cipta.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Tidak mengunggah konten yang mengandung malware, spam, atau materi tidak pantas.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Mematuhi panduan etika dan kebijakan institusi terkait.</p>
</li>
</ul>
<h3><strong>5. Hak dan Kewajiban Penyedia LMS</strong></h3>
<p class="ds-markdown-paragraph">5.1. <strong>Hak Kami</strong>:</p>
<ul>
<li>
<p class="ds-markdown-paragraph">Memodifikasi, membatasi, atau menghentikan layanan dengan pemberitahuan sebelumnya.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Menghapus konten yang melanggar Syarat ini tanpa pemberitahuan.</p>
</li>
</ul>
<p class="ds-markdown-paragraph">5.2. <strong>Kewajiban Kami</strong>:</p>
<ul>
<li>
<p class="ds-markdown-paragraph">Menyediakan Platform yang stabil dan aman sesuai kapasitas teknis.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Melindungi data pribadi Pengguna sesuai <strong>Kebijakan Privasi</strong>.</p>
</li>
</ul>
<h3><strong>6. Kepemilikan dan Hak Cipta</strong></h3>
<p class="ds-markdown-paragraph">6.1. Seluruh konten yang diunggah oleh Pengguna tetap menjadi tanggung jawab pemilik konten.<br>6.2. Pengguna dilarang mendistribusikan ulang materi pembelajaran tanpa izin pemegang hak cipta.</p>
<h3><strong>7. Pembatasan Tanggung Jawab</strong></h3>
<p class="ds-markdown-paragraph">7.1. Kami tidak bertanggung jawab atas:</p>
<ul>
<li>
<p class="ds-markdown-paragraph">Kerugian akibat penyalahgunaan akun oleh Pengguna.</p>
</li>
<li>
<p class="ds-markdown-paragraph">Gangguan teknis di luar kendali Kami (misalnya: bencana alam, pemadaman listrik).</p>
</li>
</ul>
<h3><strong>8. Perlindungan Data</strong></h3>
<p class="ds-markdown-paragraph">Pengumpulan dan pemrosesan data pribadi diatur dalam <strong>Kebijakan Privasi</strong> terpisah yang menjadi bagian dari Syarat ini.</p>
<h3><strong>9. Perubahan Syarat</strong></h3>
<p class="ds-markdown-paragraph">Kami dapat memperbarui Syarat ini sewaktu-waktu. Perubahan akan diberitahukan melalui Platform atau email.</p>
<h3><strong>10. Hukum yang Berlaku</strong></h3>
<p class="ds-markdown-paragraph">Syarat ini tunduk pada hukum <strong>[Negara/Jurisdiksi, contoh: Republik Indonesia]</strong>. Sengketa akan diselesaikan melalui jalur musyawarah atau pengadilan di <strong>[Kota, contoh: Jakarta Selatan]</strong>.</p>
<h3><strong>11. Kontak</strong></h3>
<p class="ds-markdown-paragraph">Untuk pertanyaan, hubungi:<br><strong>[Alamat Email/Official Contact]</strong><br><strong>[Alamat Kantor]</strong></p>
<p class="ds-markdown-paragraph"><strong>Dengan menggunakan LMS ini, Anda menyatakan telah membaca, memahami, dan menyetujui Syarat dan Ketentuan ini.</strong></p>'
        ]);
    }
}
