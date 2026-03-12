<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Region;
use Illuminate\Database\Seeder;
use App\Models\TransporterRate;

use function PHPSTORM_META\map;

class TransporterRateSeeder extends Seeder
{
    public function run()
    {
        // Path to your CSV file
        $csvFile = database_path('seeders/data/transporter_rates.csv');

        // Open the file in read mode
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Skip the header row
            fgetcsv($handle);

            // Loop through the CSV rows
            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                // Create the transporter rate entry
                $area = Area::where('ShortChar01', $row[1])->first();
                if (!$area) {
                    continue;
                }
                TransporterRate::create([
                    'name'       => $row[0],
                    'area_uuid' => $area->uuid,
                    'charge'     => (int)$row[4],
                    'created_by'  => 'System',
                    'updated_by' => 'System'
                ]);
            }

            // Close the file
            fclose($handle);
        }
    }
}
