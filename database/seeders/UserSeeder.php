<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'nama' => 'administrator',
            'username' => 'admin',
            'password' => Hash::make('Admin123'),
            'gender' => 'l',
            'id_role' => 1,
            'foto' => null,
        ]);
    }
}
