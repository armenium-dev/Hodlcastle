<?php namespace Tests\Models;

use Tests\TestCase;
use Zizaco\FactoryMuff\Facade\FactoryMuff;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\EmailTemplate;

class EmailTemplateTest extends TestCase
{
    use DatabaseTransactions;

    public function testEmailTemplateCreation()
    {
        $model = factory(EmailTemplate::class)->create();

        $this->assertDatabaseHas($model->table, [
            'id' => $model->id,
            'subject' => $model->subject,
        ]);
    }
}
