# LMS

## Seeding Data

```sh
php artisan migrate:fresh --seed
php artisan unor:sync
php artisan instansi:sync
php artisan user:sync {instansi_id}
```

## Environment variables

BANTARA_SIGNER_NIK=123456 # NIK untuk signer, isi dengan nik kepala BKPSDM

# Data User

[x] 'alamat',
[x] 'jenis_kelamin',  
[x] 'pendidikan',
[x] 'tingkat_pendidikan',
[x] 'tmt_golongan',
[x] 'tmt_jabatan',
[x] 'bup': 2025-{tahun_lahir}+{bup_from_api}
[x] 'eselon',
[x] 'golongan',
[x] 'jabatan',
[x] 'pangkat',
[x] 'tanggal_lahir',
[x] 'tempat_lahir'
