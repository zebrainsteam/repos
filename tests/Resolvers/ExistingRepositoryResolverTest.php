<?php

namespace Repositories\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\{Exceptions\CouldNotResolve,
    Resolvers\ExistingRepositoryResolver,
    Tests\Support\CategoryModel,
    Tests\Support\CategoryRepository,
    Tests\Support\UserModel};

class ExistingRepositoryResolverTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function exception_is_thrown_if_passed_not_existing_class()
    {
        $resolver = new ExistingRepositoryResolver();

        $this->expectException(CouldNotResolve::class);

        $resolver->resolve("Repositories\Core\Tests\Support\WrongClass");
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_resolved_without_repository()
    {
        $resolver = new ExistingRepositoryResolver();

        $this->expectException(CouldNotResolve::class);

        $resolver->resolve(UserModel::class);
    }

    /**
     * @test
     */
    public function repository_is_resolved()
    {
        $resolver = new ExistingRepositoryResolver();

        $this->assertInstanceOf(CategoryRepository::class, $resolver->resolve(CategoryModel::class));
    }
}
