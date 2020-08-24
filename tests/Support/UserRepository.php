<?php

namespace Repositories\Tests\Support;

use Repositories\ArrayRepository;
use Repositories\Parameters;
use Repositories\Result;

class UserRepository extends ArrayRepository
{
    public function __construct()
    {
        parent::__construct([
            [
                'id' => 1,
                'first_name' => 'Ivan',
                'last_name' => 'Ivanov',
            ],
        ]);
    }
}
