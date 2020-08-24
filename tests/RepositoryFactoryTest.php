<?php

namespace Repositories\Tests;

use Repositories\Tests\Support\UserRepository;
use Repositories\Exceptions\CouldNotResolve;
use Repositories\RepositoryFactory;
use Repositories\Resolvers\HardResolver;
use Repositories\ArrayRepository;

class RepositoryFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function factory()
    {
        $userRepository = $this->factory->getRepository('users');

        $this->assertTrue($userRepository instanceof UserRepository);
    }

    /**
     * @test
     */
    public function repositories_are_singletons()
    {
        $userRepository = $this->factory->getRepository('users');
        $secondUserRepository = $this->factory->getRepository('users');

        $this->assertSame($userRepository, $secondUserRepository);
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_cannot_find_implementation()
    {
        $this->expectException(CouldNotResolve::class);

        $nonExistentRepository = $this->factory->getRepository('not-resolved repository');
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_cannot_resolve_repository()
    {
        $this->expectException(CouldNotResolve::class);

        $nonExistentRepository = $this->factory->getRepository('non-existent repository');
    }

    /**
     * @test
     */
    public function callback_is_processed()
    {
        $expected = [
            [
                'id' => 17,
                'login' => 'user',
            ],
            [
                'id' => 18,
                'login' => 'operator',
            ],
        ];

        $factory = new RepositoryFactory(new HardResolver, [
            'users' => function () use ($expected) {
                return new ArrayRepository($expected);
            },
        ]);

        $this->assertSame($factory->getRepository('users'), $factory->getRepository('users'));

        $this->assertEquals(2, $factory->getRepository('users')->count());

        $this->assertEquals($expected[0], $factory->getRepository('users')->getById(17));
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_callback_cant_be_resolved()
    {
        $factory = new RepositoryFactory(new HardResolver, [
            'users' => function () {
                return new \stdClass();
            },
        ]);

        $this->expectException(CouldNotResolve::class);

        $factory->getRepository('users');
    }
}
