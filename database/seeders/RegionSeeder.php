<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['code' => '02', 'name' => 'JAWA BARAT'],
            ['code' => '03', 'name' => 'JAWA TENGAH'],
            ['code' => '04', 'name' => 'JAWA TIMUR'],
            ['code' => '01', 'name' => 'DKI JAKARTA'],
            ['code' => '07', 'name' => 'RIAU'],
            ['code' => '10', 'name' => 'LAMPUNG'],
            ['code' => '05', 'name' => 'BALI'],
            ['code' => '17', 'name' => 'MADURA'],
            ['code' => '71', 'name' => 'USA'],
            ['code' => '06', 'name' => 'NTT'],
            ['code' => '13', 'name' => 'SUMATERA UTARA'],
            ['code' => '09', 'name' => 'SULAWESI'],
            ['code' => '11', 'name' => 'SUMATERA SELATAN'],
            ['code' => '51', 'name' => 'ASIA'],
            ['code' => '12', 'name' => 'SUMATERA BARAT'],
            ['code' => '99', 'name' => 'OVERSEAS'],
        ];

        foreach ($regions as $region) {
            $isExist = Region::where('ShortChar01', $region['code'])->exists();
            if (!$isExist) {
                $region['created_by'] = 'System';
                $region['updated_by'] = 'System';
                Region::create($region);
            }
        }
    }
}
