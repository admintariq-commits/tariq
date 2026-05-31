<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['name' => 'Dar es Salaam', 'code' => 'DSM'],
            ['name' => 'Arusha', 'code' => 'ARU'],
            ['name' => 'Dodoma', 'code' => 'DOD'],
            ['name' => 'Geita', 'code' => 'GTA'],
            ['name' => 'Iringa', 'code' => 'IRI'],
            ['name' => 'Kagera', 'code' => 'KGR'],
            ['name' => 'Katavi', 'code' => 'KTV'],
            ['name' => 'Kigoma', 'code' => 'KIG'],
            ['name' => 'Kilimanjaro', 'code' => 'KIL'],
            ['name' => 'Lindi', 'code' => 'LND'],
            ['name' => 'Manyara', 'code' => 'MRY'],
            ['name' => 'Mara', 'code' => 'MRA'],
            ['name' => 'Mbeya', 'code' => 'MBE'],
            ['name' => 'Morogoro', 'code' => 'MGO'],
            ['name' => 'Mtwara', 'code' => 'MTW'],
            ['name' => 'Mwanza', 'code' => 'MWA'],
            ['name' => 'Njombe', 'code' => 'NJM'],
            ['name' => 'Pwani', 'code' => 'PWN'],
            ['name' => 'Rukwa', 'code' => 'RUK'],
            ['name' => 'Ruvuma', 'code' => 'RUV'],
            ['name' => 'Shinyanga', 'code' => 'SHY'],
            ['name' => 'Simiyu', 'code' => 'SMY'],
            ['name' => 'Singida', 'code' => 'SGA'],
            ['name' => 'Songwe', 'code' => 'SGW'],
            ['name' => 'Tabora', 'code' => 'TAB'],
            ['name' => 'Tanga', 'code' => 'TAN'],
            ['name' => 'Zanzibar North', 'code' => 'ZNW'],
            ['name' => 'Zanzibar Central/South', 'code' => 'ZCS'],
            ['name' => 'Zanzibar Urban/West', 'code' => 'ZUW'],
            ['name' => 'Pemba North', 'code' => 'PBN'],
            ['name' => 'Pemba South', 'code' => 'PBS'],
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate(['code' => $region['code']], ['name' => $region['name']]);
        }
    }
}
