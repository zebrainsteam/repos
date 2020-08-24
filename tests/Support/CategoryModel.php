<?php

namespace Prozorov\Repositories\Tests\Support;

use Prozorov\Repositories\Contracts\HasRepositoryInterface;
use Prozorov\Repositories\Contracts\RepositoryInterface;

class CategoryModel implements HasRepositoryInterface
{
    public static function getRepository(): RepositoryInterface
    {
        return new CategoryRepository();
    }
}
