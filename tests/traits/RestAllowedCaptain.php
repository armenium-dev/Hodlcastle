<?php namespace Tests\Traits;

trait RestAllowedCaptain {

    public function testIndexCaptain()
    {
        $this->setUserCaptain();

        $response = $this->responseIndex();

        $response->assertOk();
    }

    public function testCreateCaptain()
    {
        $this->setUserCaptain();

        $response = $this->responseCreate();

        $response->assertOk();
    }

    public function testStoreCaptain()
    {
        $this->setUserCaptain();

        $response = $this->responseStore();

        $response->assertStatus(302);
    }

    public function testEditCaptain()
    {
        $this->setUserCaptain();

        $response = $this->responseEdit();

        $response->assertOk();
    }

    public function testUpdateCaptain()
    {
        $this->setUserCaptain();

        $response = $this->responseUpdate();

        $response->assertStatus(302);
    }
}