<?php

namespace Repositories\Tests\Support;

use Repositories\ArrayRepository;
use Repositories\Parameters;
use Repositories\Result;

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
