<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::create([
            'name' => 'Device 1',
            'secret' => env('DEVICE_SECRET', 'default_secret'), // the secret comes from ENV or uses a default
            'current_weight' => 0, // initialize with default weight
            'previous_weight' => 0, // initialize with default previous weight
            'tolerance' => 10, // example tolerance value
            'status' => 'unstable', // default status
            'created_by' => 'System',
            'updated_by' => 'System'
        ]);
    }
}
