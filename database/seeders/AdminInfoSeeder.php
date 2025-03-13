<?php

namespace Database\Seeders;

use App\Models\Admin;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        if (Admin::count() < 3) {
            // Create a super admin
            $admin = new Admin();
            $admin->name = 'John Doe';
            $admin->email = 'admin@gmail.com';
            $admin->username = 'lms_admin';
            $admin->image = 'uploads/website-images/admin.jpg';
            $admin->password = Hash::make(1234);
            $admin->status = 'active';
            $admin->save();

            $role = Role::first();
            $admin?->assignRole($role);


            // Create a admin BKPSDM
            $admin = new Admin();
            $admin->name = 'Admin BKPSDM';
            $admin->email = 'admin@bkpsdm.com';
            $admin->username = 'lms_admin_bkpsdm';
            $admin->image = 'uploads/website-images/admin.jpg';
            $admin->password = Hash::make(1234);
            $admin->status = 'active';
            $admin->save();

            $role = Role::where('name', 'Admin BKPSDM')->first();
            $admin?->assignRole($role);

            // Create a admin OPD
            $admin = new Admin();
            $admin->name = 'Admin OPD';
            $admin->email = 'admin@opd.com';
            $admin->username = 'lms_admin_opd';
            $admin->image = 'uploads/website-images/admin.jpg';
            $admin->password = Hash::make(1234);
            // $admin->instansi_id = 20487;
            $admin->status = 'active';
            $admin->save();

            $role = Role::where('name', 'Admin OPD')->first();
            $admin?->assignRole($role);
        }
    }
}
