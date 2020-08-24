<?php

namespace Repositories\Tests\Support;

use Repositories\Contracts\HasRepositoryInterface;
use Repositories\Contracts\RepositoryInterface;

class CategoryModel implements HasRepositoryInterface
{
    public static function getRepository(): RepositoryInterface
    {
        return new CategoryRepository();
    }
}
