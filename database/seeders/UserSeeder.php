<?php

namespace Database\Seeders;

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
        $user->fill(['name' => 'Administrador', 'email' => 'admin@admin.com', 'password' => bcrypt('123456'), 'country' => 55, 'area' => 17, 'number' => 996664559])->save();
    }
}
