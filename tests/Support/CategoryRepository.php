<?php

namespace Prozorov\Repositories\Tests\Support;

use Prozorov\Repositories\ArrayRepository;
use Prozorov\Repositories\Parameters;
use Prozorov\Repositories\Result;

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
