<?php

namespace App\Console\Commands;

use App\Models\Instansi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class InstansiSync extends Command
{
    protected $signature = 'instansi:sync';


    protected $description = 'Sync Instansi';


    public function handle()
    {
        $this->info("Crawling data INSTANSI");

        $request = Http::withHeaders([
            'accept' => 'application/json',
            'x-secret' => env('ESURATAPI_SECRET'),
        ]);

        $response = $request->get(env("ESURATAPI_BASEURL") . "/internal/instansis");

        if ($response->failed()) {
            $this->error('Gagal mengambil data instansi' . 'status: ' . $response->status());
            return;
        }

        $instansiDatas = json_decode(json_encode($response->json(), true));

        foreach ($instansiDatas->data as $instansiData) {
            Instansi::updateOrCreate([
                'esurat_id' => $instansiData->uuid,
            ], [
                'unor_id' => $instansiData->unor_id,
                'name' => $instansiData->name,
            ]);
        }

        $this->info('Berhasil sinkronisasi data instansi');
    }
}
