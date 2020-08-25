<?php

namespace Repositories\Core\Tests\Resolvers;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\Resolvers\{ChainResolver, ContainerAwareResolver, HardResolver};
use Repositories\Core\Contracts\{RepositoryInterface, ResolverInterface};
use Repositories\Core\Exceptions\CouldNotResolve;
use Repositories\Core\Tests\Support\UserRepository;

class ChainResolverTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function resolving_is_passed_to_the_next_resolver()
    {
        $containerResolver = \Mockery::mock(ContainerAwareResolver::class);
        $containerResolver->shouldReceive('resolve')
            ->once()
            ->andThrow(new CouldNotResolve());

        $hardResolver = \Mockery::mock(HardResolver::class);
        $hardResolver->shouldReceive('resolve')
            ->once()
            ->andReturn(new UserRepository());

        $resolver = new ChainResolver([$containerResolver, $hardResolver]);

        $resolver->resolve('users');
    }

    /**
     * @test
     */
    public function first_resolving_is_returned_if_succeeds()
    {
        $containerResolver = \Mockery::mock(ContainerAwareResolver::class);
        $containerResolver->shouldReceive('resolve')
            ->once()
            ->andReturn(new UserRepository());

        $hardResolver = \Mockery::mock(HardResolver::class);
        $hardResolver->shouldNotReceive('resolve');

        $resolver = new ChainResolver([$containerResolver, $hardResolver]);

        $resolver->resolve('users');
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_none_resolvers_can_resolve_the_repository()
    {
        $containerResolver = \Mockery::mock(ContainerAwareResolver::class);
        $containerResolver->shouldReceive('resolve')
            ->once()
            ->andThrow(new CouldNotResolve());

        $hardResolver = \Mockery::mock(HardResolver::class);
        $hardResolver->shouldReceive('resolve')
            ->once()
            ->andThrow(new CouldNotResolve());

        $this->expectException(CouldNotResolve::class);

        $resolver = new ChainResolver([$containerResolver, $hardResolver]);

        $resolver->resolve('users');
    }

    /**
     * @test
     */
    public function resolver_can_be_passed_as_a_string()
    {
        $this->expectException(CouldNotResolve::class);

        $resolver = new ChainResolver([HardResolver::class]);

        $resolver->resolve('users');
    }

    /**
     * @test
     */
    public function resolver_can_only_be_class_implementing_interface()
    {
        $this->expectException(\InvalidArgumentException::class);

        $resolver = new ChainResolver(['I am not a resolver']);

        $resolver->resolve('users');
    }
}
