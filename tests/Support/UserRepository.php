<?php

namespace Prozorov\Repositories\Tests\Support;

use Prozorov\Repositories\ArrayRepository;
use Prozorov\Repositories\Parameters;
use Prozorov\Repositories\Result;

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
