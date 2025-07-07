<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provinsi;

class ProvinsiSeeder extends Seeder
{
    public function run(): void
    {
        $provinsis = [
            ['nama' => 'Aceh', 'ibukota' => 'Banda Aceh', 'populasi' => 5575000, 'luas_wilayah' => 57956.00],
            ['nama' => 'Sumatera Utara', 'ibukota' => 'Medan', 'populasi' => 15160000, 'luas_wilayah' => 72981.23],
            ['nama' => 'Jawa Barat', 'ibukota' => 'Bandung', 'populasi' => 49350000, 'luas_wilayah' => 35377.76],
            // Tambahkan data provinsi lainnya dari https://wilayah.id/
        ];

        foreach ($provinsis as $provinsi) {
            Provinsi::create($provinsi);
        }
    }
}
