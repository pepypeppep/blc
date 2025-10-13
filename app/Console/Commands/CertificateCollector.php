<?php

namespace App\Console\Commands;

use App\Models\CertificateCollection;
use App\Models\User;
use Illuminate\Console\Command;
use App\Services\CertificateService;

class CertificateCollector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect and store certificate data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Collecting certificate data...');
        $certificateService = new CertificateService();
        // $user_id = 1;
        $users = User::where('role', 'student')->where('status', 'active')->get();
        foreach ($users as $user) {
            $this->info('Collecting certificates for user: ' . $user->username);
            $user_id = $user->id;
            $results = $certificateService->getCertificatesForUserCollector($user_id);
            if ($results['success']) {
                foreach ($results as $key => $result) {
                    $this->info('Storing certificate: ' . $result['name']);
                    CertificateCollection::updateOrCreate([
                        'user_id' => $user_id,
                        'category' => $result['category'],
                        'title' => $result['name'],
                        'date' => $result['date'],
                    ], [
                        'jp' => $result['jp'],
                        'periode' => $result['periode'],
                        'triwulan' => $result['triwulan'],
                        'url' => $result['url'],
                    ]);
                }
            }
        }
        $this->info('Certificate data collected successfully.');
        return 0;
    }
}
