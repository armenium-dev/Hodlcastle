<?php namespace Tests\Controllers;

use App;
use App\User;
use Mockery;
use Tests\TestCase;
use App\Models\Company;
use App\Http\Controllers\CompanyController;
use App\Http\Requests\UpdateCompanyRequest;
use Tests\Traits\RestForbiddenCustomer;
use Tests\Traits\RestAllowedCaptain;

class CompanyControllerTest extends TestCase
{
    use RestForbiddenCustomer;
    use RestAllowedCaptain;

    public $view_dir = 'companies';

    public $url = 'companies';

    public $var_single = 'company';

    public function setUp()
    {
        parent::setUp();

        $this->model = Company::first();
    }

    public function testIndexCaptain()
    {
        $company = factory('App\Models\Company')->create();
        $response = $this->get('/' . $this->url);

        $response->assertOk();
        $response->assertViewIs($this->view_dir . '.index');
        $response->assertSee($company->name);
    }

    public function testShowCustomerNotHisCompanyFails()
    {
        $this->setUserCustomer();

        $this->setCompanyDoesntBelongToCurrentUser();

        $response = $this->responseShow();

        $response->assertForbidden();
    }

    public function testShowCustomerHisCompanyFails()
    {
        $this->setUserCustomer();
        $this->setCompanyBelongsToCurrentUser();

        $response = $this->responseShow();

        $response->assertForbidden();
//        $response->assertOk();
//        $response->assertViewHas([$this->var_single]);
//        $response->assertViewIs($this->view_dir . '.show');

    }

    public function testEditCaptain()
    {
        $this->setCompanyDoesntBelongToCurrentUser();

        $response = $this->responseEdit();

        $response->assertViewHas([$this->var_single]);
        $response->assertViewIs($this->view_dir . '.edit');
    }

    public function testEditCustomerNotHisCompanyFails()
    {
        $this->setUserCustomer();

        $this->setCompanyDoesntBelongToCurrentUser();

        $response = $this->responseEdit();

        $response->assertForbidden();

    }

    public function testEditCustomerHisCompanyFails()
    {
        $this->setUserCustomer();
        $this->setCompanyBelongsToCurrentUser();

        $response = $this->responseEdit();

        $response->assertForbidden();
//        $response->assertOk();
//        $response->assertViewHas([$this->var_single]);
//        $response->assertViewIs($this->view_dir . '.edit');
    }

    protected function setCompanyDoesntBelongToCurrentUser()
    {
        $this->model = Company::where('id', '!=', $this->user->company_id)->first();
    }

    protected function setCompanyBelongsToCurrentUser()
    {
        $this->model = $this->user->company;
    }
}