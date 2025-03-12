# LMS

## Seeding Data

```sh
php artisan migrate:fresh --seed
php artisan unor:sync
php artisan instansi:sync
php artisan user:sync {instansi_id}
```

# Data User

[ ] 'jenis_kelamin',  
[x] 'tempat_lahir'
[x] 'tanggal_lahir',
[ ] 'agama',
[ ] 'alamat',
[ ] 'tingkat_pendidikan',
[ ] 'pendidikan',
[x] 'golongan',
[x] 'pangkat',
[ ] 'tmt_golongan',
[x] 'jabatan',
[ ] 'tmt_jabatan',
[x] 'eselon',
[x] 'bup': 2025-{tahun_lahir}+{bup_from_api}
