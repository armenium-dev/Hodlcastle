<?php namespace Tests\Traits;

trait RestForbiddenCustomer {


    public function testIndexCustomerFails()
    {
        $this->setUserCustomer();

        $response = $this->responseIndex();

        $response->assertForbidden();
    }

    public function testCreateCustomerFails()
    {
        $this->setUserCustomer();

        $response = $this->responseCreate();

        $response->assertForbidden();
    }

    public function testStoreCustomerFails()
    {
        $this->setUserCustomer();

        $response = $this->responseStore();

        $response->assertForbidden();
    }

    public function testEditCustomerFails()
    {
        $this->setUserCustomer();

        $response = $this->responseEdit();

        $response->assertForbidden();
    }

    public function testUpdateCustomerFails()
    {
        $this->setUserCustomer();

        $response = $this->responseUpdate();

        $response->assertForbidden();
    }
}