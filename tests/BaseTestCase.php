<?php

namespace Prozorov\Repositories\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Prozorov\Repositories\RepositoryFactory;
use Prozorov\Repositories\Resolvers\HardResolver;
use Prozorov\Repositories\Tests\Support\UserRepository;

class BaseTestCase extends MockeryTestCase
{
    /**
     * @var RepositoryFactory $factory
     */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new RepositoryFactory(new HardResolver, [
            'users' => UserRepository::class,
            'non-existent repository' => '\Non\Existent\Repository',
        ]);
    }
}
