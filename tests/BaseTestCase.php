<?php

namespace Repositories\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\RepositoryFactory;
use Repositories\Resolvers\HardResolver;
use Repositories\Tests\Support\UserRepository;

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
