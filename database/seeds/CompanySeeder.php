<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $data = [
            [
                'name' => 'Demo (Verify Security)',
                'status' => rand(0, 2),
                'expires_at' => \Carbon\Carbon::now()->addYear(),
            ],
            [
                'name' => 'Cyberdyne',
                'status' => rand(0, 2),
                'expires_at' => \Carbon\Carbon::now()->addYear(),
            ],
            [
                'name' => 'IngenS',
                'status' => rand(0, 2),
                'expires_at' => \Carbon\Carbon::now()->addYear(),
            ],
            [
                'name' => 'Biffco',
                'status' => rand(0, 2),
                'expires_at' => \Carbon\Carbon::now()->addYear(),
            ],
            [
                'name' => 'Gotham City',
                'status' => rand(0, 2),
                'expires_at' => \Carbon\Carbon::now()->addYear(),
            ],
        ];

        foreach ($data as $datum) {
            $company = Company::create($datum);
        }

//        for ($i=0; $i<10; $i++) {
//            Company::create([
//                'name' => $faker->company,
//                'status' => rand(0, 2),
//                'expires_at' => \Carbon\Carbon::now()->addYear(),
//            ]);
//        }
    }
}