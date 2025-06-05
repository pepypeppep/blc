<?php

namespace App\Console\Commands;

use App\Models\Unor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UnorSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unor:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Unor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Crawling data UNOR");

        $response = Http::timeout(60)->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SAPA_USERNAME') . ':' . env('SAPA_PASSWORD')),
        ])->get('https://asn.bantulkab.go.id/ws/showunors.php');


        if ($response->failed()) {
            $this->error('Gagal mengambil data user' . 'status: ' . $response->status());
            return;
        }

        $unorsData = $response->json('result');

        foreach ($unorsData as $unorData) {
            Unor::updateOrCreate([
                'id' => $unorData['id'],
            ], [
                'parent_id' => $unorData['parent_id'],
                'name' => $unorData['name'],
            ]);
        }

        $this->info('Berhasil sinkronisasi data unor');
    }
}
