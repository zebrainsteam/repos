<?php

namespace Repositories\Core\Tests\Support;

use Repositories\Core\Contracts\HasRepositoryInterface;
use Repositories\Core\Contracts\RepositoryInterface;

class CategoryModel implements HasRepositoryInterface
{
    public static function getRepository(): RepositoryInterface
    {
        return new CategoryRepository();
    }
}
