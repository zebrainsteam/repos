<?php

namespace Repositories\Core\Tests\Support;

use Repositories\Core\ArrayRepository;
use Repositories\Core\Parameters;
use Repositories\Core\Result;

class CategoryRepository extends ArrayRepository
{
    public function __construct()
    {
        parent::__construct([
            [
                'id' => 1,
                'name' => 'Category1',
            ],
        ]);
    }
}
