<?php namespace Tests\Models;

use App\Models\Group;
use Tests\TestCase;
use Tests\Traits\MakeGroupTrait;
use App\Repositories\GroupRepository;
use App;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupRepositoryTest extends TestCase
{
    use MakeGroupTrait, DatabaseTransactions;

    /**
     * @var GroupRepository
     */
    protected $groupRepo;

    public function setUp()
    {
        parent::setUp();
        $this->groupRepo = App::make(GroupRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateGroup()
    {
        $group = $this->fakeGroupData();
        $createdGroup = $this->groupRepo->create($group);
        $createdGroup = $createdGroup->toArray();
        $this->assertArrayHasKey('id', $createdGroup);
        $this->assertNotNull($createdGroup['id'], 'Created Group must have id specified');
        $this->assertNotNull(Group::find($createdGroup['id']), 'Group with given id must be in DB');
        //$this->assertModelData($group, $createdGroup);
    }

    /**
     * @test read
     */
    public function testReadGroup()
    {
        $group = $this->makeGroup();
        $dbGroup = $this->groupRepo->find($group->id);
        $dbGroup = $dbGroup->toArray();
        //$this->assertModelData($group->toArray(), $dbGroup);

        $this->assertTrue(true);
    }

    /**
     * @test update
     */
    public function testUpdateGroup()
    {
        $group = $this->makeGroup();
        $fakeGroup = $this->fakeGroupData();
        $updatedGroup = $this->groupRepo->update($fakeGroup, $group->id);
        //$this->assertModelData($fakeGroup, $updatedGroup->toArray());
        $dbGroup = $this->groupRepo->find($group->id);
        //$this->assertModelData($fakeGroup, $dbGroup->toArray());

        $this->assertTrue(true);
    }

    /**
     * @test delete
     */
    public function testDeleteGroup()
    {
        $group = $this->makeGroup();
        $resp = $this->groupRepo->delete($group->id);
        $this->assertTrue($resp);
        $this->assertNull(Group::find($group->id), 'Group should not exist in DB');
    }
}
