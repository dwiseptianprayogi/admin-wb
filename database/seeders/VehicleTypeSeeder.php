<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            [
                'name' => "COLT DIESEL",
                'code' => "01",
                'tolerance' => "100",
                'weight_standart' => "1000",
            ],
            [
                'name' => "FUSO",
                'code' => "02",
                'tolerance' => "100",
                'weight_standart' => "1000",
            ],
            [
                'name' => "TRONTON",
                'code' => "03",
                'tolerance' => "100",
                'weight_standart' => "1000",
            ],
            [
                'name' => "GANDENGAN",
                'code' => "04",
                'tolerance' => "100",
                'weight_standart' => "1000",
            ],
            [
                'name' => "TRUCK MINI",
                'code' => "05",
                'tolerance' => "100",
                'weight_standart' => "1000",
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            $isExist = VehicleType::where('ShortChar01', $vehicleType['code'])->exists();
            if (!$isExist) {
                $vehicleType['created_by'] = 'System';
                $vehicleType['updated_by'] = 'System';
                VehicleType::create($vehicleType);
            }
        }
    }
}
