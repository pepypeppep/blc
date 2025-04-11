<?php

namespace Modules\Order\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Order\app\Models\Enrollment;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->call([
            Enrollment::class,
        ]);
    }
}
