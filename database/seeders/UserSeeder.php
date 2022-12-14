<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fill(['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => bcrypt('123456')])->save();

        // $customer = new Customer();
        // $customer->fill(['email' => 'admin@admin.com', 'password' => bcrypt('123456')])->save();
    }
}
