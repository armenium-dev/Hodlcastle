<?php namespace Tests\Traits;

trait CrudResponse
{
    protected function responseIndex()
    {
        return $this->call('GET', $this->url);
    }

    protected function responseCreate()
    {
        return $this->call('GET', $this->url . '/create');
    }

    protected function responseStore()
    {
        return $this->call('POST', $this->url);
    }

    protected function responseShow()
    {
        $response = $this->call('GET', $this->url . '/' . $this->model->id);

        return $response;
    }

    protected function responseEdit()
    {
        $response = $this->call('GET', $this->url . '/' . $this->model->id . '/edit');

        return $response;
    }

    protected function responseUpdate()
    {
        return $this->call('PUT', $this->url . '/' . $this->model->id);
    }

    protected function responseCopy()
    {
        $response = $this->call('GET', $this->url . '/' . $this->model->id . '/copy');

        return $response;
    }

    protected function responseDelete()
    {
        return $this->call('DELETE', $this->url . '/' . $this->model->id);
    }
}