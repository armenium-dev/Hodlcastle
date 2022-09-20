<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Group;
use App\Models\Recipient;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        factory(Group::class, 10)
            ->create()
            ->each(function ($g) {
                $g->recipients()->attach(factory(App\Models\Recipient::class, 5)->create()->pluck('id'));
            })
        ;

        $test_recipient = factory(Recipient::class)->state('test')->create();
        $test_group = factory(Group::class)->state('test')->create();

        $test_group->recipients()->attach($test_recipient->id);
    }
}
