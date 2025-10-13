# Coaching Docs

# Todo
## [ ] Modul coaching certificate. Tambahkan module untuk memilih sertifikat pada admin coaching. (Bisa seperti mentoring)

- [ ] Pemilihan jenis sertifikat
		+ [x] Jenis sertifikat yang dipilih akan di simpan di coaching.certificate_id
		+ [x] Admin bisa pilih sertifikat dan penandatangan walaupun belum coaching belum selesai

- [ ] Pemilihan penandatangan
		+ [x] Penandatangan yang dipilih akan di simpan di coaching_signers
		+ [x] TTE depan : default terpilih kepala BKPSDM
		+ [x] TTE belakang : sesuai pilihan admin
		+ [x] Label dropdown penandatangan menampilkan nama-jabatan-instansi

- [ ] Generate sertifikat
		+ [ ] kata kata di sertifikat coaching berbeda, jadi buat admin harus buat template untuk sertifikat caching sendiri
		+ [x] Setelah memilih sertifikat dan penandatangan, langsung generate sertifikat agar bisa di preview.
		+ [ ] Acuan generate jumlah sertifikat dari tabel coaching_users && coaching_users.final_report NOT NULL
		+ [x] tiap coachee sertifikat sendiri sendiri
		+ [x] simpan di coaching_users.certificate_path

- [ ] Kirim ke bantara
		+ [ ] Tombol kirim ke bantara muncul ketika status coaching = "Verifikasi"
		+ [x] urutan TTE: halaman belakang -> halaman depan
		+ [ ] Tambahkan tombol batalkan pengajuan TTE, ketika di klik maka akan membatalkan pengajuan TTE di bantara
		+ [ ] Verif berjenjang : verif mentor/coach kemudian penandatangan belakang lalu penandatangan depan
		+ [ ] Setelah klik kirim ke bantara, tidak perlu menunggu callback, langsung set menjadi selesai

- [ ] Callback dari bantara
		+ [x] simpan di coaching_users.signed_certificate_path berdasarkan document_id



