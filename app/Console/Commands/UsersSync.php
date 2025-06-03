<?php

namespace App\Console\Commands;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UsersSync extends Command
{

    protected $signature = 'users:sync {instansi}';


    protected $description = 'Sync users';

    public function handle()
    {
        $instansiID = $this->argument('instansi');

        if (empty($instansiID)) {
            $this->error('Instansi ID is required');
            return;
        }

        $instansi = Instansi::where('id', $instansiID)->firstOrFail();

        if ($instansi->unor_id == null) {
            $this->error('Instansi ID doesn\'t have UNOR ID');
            return;
        }

        $this->info("Crawling data users on " . $instansi->name);

        $response = Http::timeout(60)->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SAPA_USERNAME') . ':' . env('SAPA_PASSWORD')),
        ])
            ->post('https://asn.bantulkab.go.id/ws/showpegbyunormt.php', [
                'unor_id' => $instansi->unor_id
            ]);
        if ($response->failed()) {
            $this->error('Gagal mengambil data user' . 'status: ' . $response->status());
            return;
        }


        $usersData = $response->json('result');

        foreach ($usersData as $user) {
            $batasUsiaPensiun = now()->year - substr($user['tanggal_lahir'], 0, 4) + $user['bup'];

            // unor id from api has prefix 0
            // ex: 020298 to 20298
            $unorID = null;
            if (substr($user['id_unor'], 0, 1) == '0') {
                $unorID =  substr($user['id_unor'], 1);
            } else {
                $unorID = $user['id_unor'];
            }

            $nineboxData = Http::get('https://asn.bantulkab.go.id/makansajaapi/api/external/pegawai/' . $user['nip'])
                ->json();

            User::updateOrCreate([
                'username' => $user['nip'],
            ], [
                'instansi_id' => $instansi->id,
                'unor_id' => $unorID,
                'name' => $user['nama_lengkap'],
                'nip' => $user['nip'],
                'jabatan' => $user['jabatan'],
                'jenis_kelamin' => $user['jenis_kelamin'],
                'tempat_lahir' => $user['tempat_lahir'],
                'tanggal_lahir' => $user['tanggal_lahir'],
                'bup' => $batasUsiaPensiun,
                'golongan' => $user['golongan'],
                'pangkat' => $user['pangkat'],
                'jabatan' => $user['jabatan'],
                'eselon' => $user['Eselon'],
                'alamat' => $user['alamat'],
                'jenis_kelamin' => $user['jenis_kelamin'],
                'pendidikan' => $user['pendidikan'],
                'tingkat_pendidikan' => $user['tingkat_pendidikan'],
                'tmt_golongan' => $user['tmt_golongan'],
                'tmt_jabatan' => $user['tmt_jabatan'],
                'asn_status' => $user['jenis_asn'],
                'ninebox' => $nineboxData->data->ninebox
            ]);
        }

        $this->info('Berhasil sinkronisasi data user');
    }
}
