<?php

namespace Repositories\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\RepositoryFactory;
use Repositories\Core\Resolvers\HardResolver;
use Repositories\Core\Tests\Support\UserRepository;

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
