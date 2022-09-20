<?php
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Cyberdyne lunch menu',
                'handle' => 'lunch_menu',
                'subject' => 'Cyberdyne lunch menu',
                'company_id' => 1,
                'text' => 'Cyberdyne lunch menu',
                'html' => '<p>Cyberdyne lunch menu</p>',
                'lang' => '',
                'tags' => '',
            ],
            [
                'name' => 'Free tickets',
                'handle' => 'free_tickets',
                'subject' => 'Free tickets',
                'company_id' => 1,
                'text' => 'Free tickets',
                'html' => '<p>Free tickets</p>',
                'lang' => '',
                'tags' => '',
            ],
            [
                'name' => 'Public example',
                'handle' => 'pub_ex',
                'subject' => 'Public example',
                'company_id' => 1,
                'text' => 'Public example',
                'html' => '<p>Public example</p>',
                'lang' => '',
                'tags' => '',
                'is_public' => 1,
            ],
            [
                'name' => 'URLtest',
                'handle' => 'urltest',
                'subject' => 'URLtest subject',
                'company_id' => 1,
                'text' => 'Public example',
                'html' => '<html>Line 1: {{.URL}}<br>Line 2: <a href="{{.URL}}">I like turtles</a></html>',
                'lang' => '',
                'tags' => '',
                'is_public' => 1,
            ],
        ];

        foreach ($data as $attrs) {
            EmailTemplate::create($attrs);
        }
    }
}