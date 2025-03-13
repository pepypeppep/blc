<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminInstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Admin();
        $admin->name = 'Admin Diskominfo';
        $admin->email = 'admin@diskominfo.com';
        $admin->username = 'admin.lms.diskominfo';
        $admin->instansi_id = 19;
        $admin->image = 'uploads/website-images/admin.jpg';
        $admin->password = Hash::make(1234);
        $admin->status = 'active';
        $admin->save();

        $role = Role::where('name', 'Admin OPD')->first();
        $admin?->assignRole($role);
    }
}
