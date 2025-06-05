<?php

namespace App\Console\Commands;

use App\Models\UnorJenis;
use Illuminate\Console\Command;

class UnorJenisSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unorjenis:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Crawling data unor jenis");

        $unorJenis = [
            [
                "id" => 1,
                "name" => "Bupati",
                "order" => 1
            ],
            [
                "id" => 2,
                "name" => "Wakil Bupati",
                "order" => 2
            ],
            [
                "id" => 3,
                "name" => "Sekretariat Daerah",
                "order" => 3
            ],
            [
                "id" => 4,
                "name" => "Staf Ahli",
                "order" => 4
            ],
            [
                "id" => 5,
                "name" => "Asisten",
                "order" => 5
            ],
            [
                "id" => 6,
                "name" => "Dinas",
                "order" => 6
            ],
            [
                "id" => 7,
                "name" => "Badan",
                "order" => 7
            ],
            [
                "id" => 8,
                "name" => "Inspektorat",
                "order" => 8
            ],
            [
                "id" => 9,
                "name" => "Sekretariat DPRD",
                "order" => 9
            ],
            [
                "id" => 10,
                "name" => "Sekretariat KPU",
                "order" => 10
            ],
            [
                "id" => 11,
                "name" => "Rumah Sakit Umum",
                "order" => 11
            ],
            [
                "id" => 12,
                "name" => "Satuan",
                "order" => 12
            ],
            [
                "id" => 13,
                "name" => "Kantor",
                "order" => 13
            ],
            [
                "id" => 14,
                "name" => "Bagian Setda",
                "order" => 14
            ],
            [
                "id" => 15,
                "name" => "Kecamatan / Kapanewon",
                "order" => 15
            ],
            [
                "id" => 16,
                "name" => "Sekretariat",
                "order" => 16
            ],
            [
                "id" => 17,
                "name" => "Bidang",
                "order" => 17
            ],
            [
                "id" => 18,
                "name" => "Seksi",
                "order" => 18
            ],
            [
                "id" => 19,
                "name" => "Sub Bidang",
                "order" => 20
            ],
            [
                "id" => 20,
                "name" => "Sub Bagian",
                "order" => 21
            ],
            [
                "id" => 21,
                "name" => "UPTD Puskesmas",
                "order" => 22
            ],
            [
                "id" => 22,
                "name" => "UPT",
                "order" => 23
            ],
            [
                "id" => 23,
                "name" => "Bagian",
                "order" => 19
            ],
            [
                "id" => 24,
                "name" => "Direktorat",
                "order" => 20
            ],
            [
                "id" => 25,
                "name" => "Sekolah",
                "order" => 24
            ],
            [
                "id" => 26,
                "name" => "Inspektur Pembantu",
                "order" => 25
            ],
            [
                "id" => 27,
                "name" => "Khusus",
                "order" => 0
            ],
            [
                "id" => 28,
                "name" => "Kapanewon",
                "order" => 15
            ]
        ];

        foreach ($unorJenis as $key => $jenis) {
            UnorJenis::updateOrCreate([
                'id' => $jenis['id'],
            ], [
                'name' => $jenis['name'],
                'order' => $jenis['order']
            ]);
        }

        $this->info('Berhasil sinkronisasi data unor jenis');
    }
}
