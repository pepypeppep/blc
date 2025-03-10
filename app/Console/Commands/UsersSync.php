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

        $instansi = Instansi::where('esurat_id', $instansiID)->firstOrFail();

        $this->info("Crawling data users");

        $request = Http::withHeaders([
            'accept' => 'application/json',
            'x-secret' => env('ESURATAPI_SECRET'),
        ]);

        $response = $request->get(env("ESURATAPI_BASEURL") . sprintf("/internal/instansis/%s/users", $instansiID));

        if ($response->failed()) {
            $this->error('Gagal mengambil data user' . 'status: ' . $response->status());
            return;
        }

        $usersData = json_decode(json_encode($response->json(), true));
        // dd($usersData);

        //     +"username": "199401162020121006"
        //   +"unor_id": 20299
        //   +"nama": "KRESNA RAKHMAN HUTAMA, A.Md."
        //   +"nip": "199401162020121006"
        //   +"jabatan": "Pranata Komputer Pelaksana"
        //   +"eselon": ""
        //   +"type": "jft"


        foreach ($usersData->data as $user) {
            User::updateOrCreate([
                'username' => $user->username,
            ], [
                // 'unor_id' => $user->unor_id,
                'instansi_id' => $instansi->id,
                'name' => $user->nama,
                'nip' => $user->nip,
                'jabatan' => $user->jabatan,
                // 'eselon' => $user->eselon,
            ]);
        }

        $this->info('Berhasil sinkronisasi data instansi');
    }
}
