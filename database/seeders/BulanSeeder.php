<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bulan;

class BulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bulan::truncate();
        
        DB::table('bulan')->insert([
            ['kode' => '01', 'nama' => 'januari'],
            ['kode' => '02', 'nama' => 'februari'],
            ['kode' => '03', 'nama' => 'maret'],
            ['kode' => '04', 'nama' => 'april'],
            ['kode' => '05', 'nama' => 'mei'],
            ['kode' => '06', 'nama' => 'juni'],
            ['kode' => '07', 'nama' => 'juli'],
            ['kode' => '08', 'nama' => 'agustus'],
            ['kode' => '09', 'nama' => 'september'],
            ['kode' => '10', 'nama' => 'oktober'],
            ['kode' => '11', 'nama' => 'november'],
            ['kode' => '12', 'nama' => 'desember'],
        ]);
    }
}
