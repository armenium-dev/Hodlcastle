<?php namespace App\Helpers;

use App\Repositories\EmailTemplateRepository;
use Illuminate\Contracts\Cache\Repository;

class EmailTemplates extends \JDT\LaravelEmailTemplates\EmailTemplates
{
    public function __construct(
        EmailTemplateRepository $emailTemplateRepository,
        Repository $cache,
        $stylesheet = null
    ) {
        $this->repository = $emailTemplateRepository;
        $this->cache = $cache;
        $this->stylesheet = $stylesheet;
    }
}