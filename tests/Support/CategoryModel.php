<?php

namespace Prozorov\Repositories\Tests\Support;

use Prozorov\Repositories\Contracts\HasRepository;
use Prozorov\Repositories\Contracts\RepositoryInterface;

class CategoryModel implements HasRepository
{
    public function getRepository(): RepositoryInterface
    {
        return new CategoryRepository();
    }
}