<?php

namespace App\Console\Commands;

use App\Models\Unor;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class EmployeeCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get employee data from SAPA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn("Crawling data UNOR");

        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SAPA_USERNAME') . ':' . env('SAPA_PASSWORD')),
        ])->get("https://asn.bantulkab.go.id/ws/showunors.php");

        $response = json_decode(json_encode($request->json(), true));

        $resp = $response->result;
        foreach ($resp as $key => $unor) {
            $this->info("[$key] " . $unor->name);
            Unor::updateOrCreate([
                'id' => $unor->id,
            ], [
                'parent_id' => $unor->parent_id,
                'name' => $unor->name
            ]);
        }

        $request2 = Http::timeout(660)->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SAPA_USERNAME') . ':' . env('SAPA_PASSWORD')),
        ])
            ->get('https://asn.bantulkab.go.id/ws/showpegbyunormt.php');
        $response2 = json_decode(json_encode($request2->json(), true));

        $responses = collect($response2->result);

        foreach ($responses as $key => $response) {
            $this->info("[$key] " . $response->nip);

            if ($response->id_unor[0] == 0) {
                $unorId = substr($response->id_unor, 1);
            } else {
                $unorId = $response->id_unor;
            }

            if ($unorId < 20000) {
                $unorId = null;
            }

            User::updateOrCreate([
                'nip' => $response->nip,
            ], [
                'unor_id' => $unorId,
                'instansi_id' => $response->id_unor_L1,
                'name' => $response->nama_lengkap
            ]);
        }
    }
}
