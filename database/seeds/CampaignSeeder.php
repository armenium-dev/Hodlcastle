<?php
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Landing;
use App\Models\Domain;
use App\Models\Group;
use App\Models\Company;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i<2; $i++) {
            Campaign::create([
                'name' => $faker->word,
                //'email_template_id' => EmailTemplate::inRandomOrder()->first()->id,
                //'landing_id' => Landing::inRandomOrder()->first()->id,
                //'domain_id' => Domain::inRandomOrder()->first()->id,
                //'group_id' => Group::inRandomOrder()->first()->id,
                'company_id' => Company::inRandomOrder()->first()->id,
            ]);
        }
    }
}