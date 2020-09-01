<?php

namespace Repositories\Core\Tests\Support;

use Repositories\Core\ArrayRepository;
use Repositories\Core\Parameters;
use Repositories\Core\Result;

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
