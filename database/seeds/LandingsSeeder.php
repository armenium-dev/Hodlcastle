<?php
use Illuminate\Database\Seeder;
use App\Models\Landing;
use App\Models\Company;

class LandingsSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        Landing::create([
            'company_id' => 0,
            'name' => 'default',
            //'content' => '<p>' . implode(' ', $faker->words(3)) . '</p>',
            'content' => '',
            //'redirect_url' => $faker->url,
            'redirect' => 1,
            'capture_credentials' => 0,
        ]);

        for ($i=0; $i<10; $i++) {
            Landing::create([
                'company_id' => Company::inRandomOrder()->first()->id,
                'name' => $faker->word,
                //'content' => '<p>' . implode(' ', $faker->words(3)) . '</p>',
                'content' => '',
                //'redirect_url' => $faker->url,
                'redirect' => 1,
                'capture_credentials' => rand(0, 1),
            ]);
        }
    }
}