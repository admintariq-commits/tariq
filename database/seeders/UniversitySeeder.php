<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $defaultRegionId = Region::first()->id ?? null;

        if (! $defaultRegionId) {
            return;
        }

        $universities = [
            'University of Dar es Salaam',
            'University of Dodoma',
            'Sokoine University of Agriculture',
            'Muhimbili University of Health and Allied Sciences',
            'Mzumbe University',
            'The Open University of Tanzania',
            'Mbeya University of Science and Technology',
            'Kilimanjaro Christian Medical University College',
            'St. Augustine University of Tanzania',
            'Tanzania International University',
            'Dar es Salaam Institute of Technology',
            'Nelson Mandela African Institution of Science and Technology',
            'Dar es Salaam University College of Education',
            'University of Bagamoyo',
            'Tanzania Institute of Accountancy',
            'Mkwawa University College of Education',
            'Moshi Co-operative University',
            'St. Joseph University in Tanzania',
            'Tumaini University Makumira',
            'University of Arusha',
            'Mwenge Catholic University',
            'State University of Zanzibar',
            'Zanzibar University',
            'Institute of Finance Management',
            'Dodoma Institute of Technology',
            'Kilimanjaro Institute of Technology',
            'University of Iringa',
            'Mkwawa University',
            'Mbeya College of Health and Allied Sciences',
            'Tengeru Institute of Community Development',
            'University of Mtwara',
            'Shinyanga University College',
            'Katavi University College',
            'Rukwa University College',
            'Songwe Technical Institute',
            'Tanga University College',
            'Pwani University College',
            'Kagera Agricultural Institute',
            'Lindi University College',
            'Manyara Technical Institute',
            'Ruvuma University College',
            'Geita Institute of Technology',
            'Simiyu Science College',
            'Njombe College of Education',
            'Pemba Institute of Science and Technology',
            'Mwanza Institute of Business and Technology',
            'Dar es Salaam School of Social Sciences',
            'Ubungo Institute of Engineering',
            'International University of Health and Public Services',
            'Central and South Zanzibar Technical University',
        ];

        foreach ($universities as $name) {
            University::firstOrCreate(
                ['name' => $name],
                ['region_id' => $defaultRegionId]
            );
        }
    }
}
