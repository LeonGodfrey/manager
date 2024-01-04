<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //  \App\Models\Organization::factory(10)->create(); // Seed the organization table

        Organization::create([
            'org_name' => 'Super Admin',
            'org_country' => 'Uganda',
            'currency_code' => 'UGX',
            'incorporation_date' => '2023-01-01',
            'business_reg_no' => '1234567',
            'manager_name' => 'Ssegawa Godfrey',
            'manager_contact' => '+25675443252'
        ]);

        Branch::create([
            'org_id' => 1,
            'branch_name' => 'Head Office',
            'branch_phone' => '+25675443252',
            'branch_email' => 'ho@gmail.com',
            'branch_prefix' => 'HO',
            'branch_street_address' => 'Naluvule',
            'branch_city' => 'Kampala',
            'branch_district' => 'Wakiso',
            'branch_postcode' => 'P.O.BOX 123',
            'status' => 'active'
        ]);

        // \App\Models\User::factory(10)->create();

        //  \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'org_id' => 1,
            'branch_id' => 1,
            'name' => 'Ssegawa Godfrey',
            'user_name' => 'Ssegawa',
            'user_phone' => '+25675443252',
            'email' => 'ssegodfrey171@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password           
        ]);

        //seed accounts
        Account::create([
            'org_id' => 1,
            'name' => 'Head Office',
            'type' => 'Asset',
            'subtype' => 'Vualt Account'
        ]);
    }
}
