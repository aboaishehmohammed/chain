<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'chain',
            'email' => 'admin@admin.com',
            'user_type' => 'hr_manager',
            'status' => 'active',
            'password' => bcrypt('12345678'),
            'created_at'=>now()
        ]);
    }
}
