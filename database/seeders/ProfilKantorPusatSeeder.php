<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilKantorPusatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mst_profil_kantor')->insert([
            'kd_cabang' => '000',
            'masa_pajak' => '07-2023',
            'tanggal_lapor' => '2023-01-20',
            'npwp_pemotong' => '019419159631000',
            'nama_pemotong' => 'BPR SARIBUMI',
            'telp' => '0315677844',
            'email' => 'bprsaribumi@gmail.com',
            'npwp_pemimpin_cabang' => '247504327618000',
            'nama_pemimpin_cabang' => 'AGUNG SOEPRIHATMANTO',
        ]);
    }
}
