<?php

namespace Prozorov\Repositories\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Prozorov\Repositories\{Exceptions\CouldNotResolve,
    Resolvers\SelfResolver,
    Tests\Support\CategoryModel,
    Tests\Support\CategoryRepository,
    Tests\Support\UserModel};

class SelfResolverTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function exception_is_thrown_if_passed_not_existing_class()
    {
        $resolver = new SelfResolver();

        $this->expectException(CouldNotResolve::class);

        $resolver->resolve("Prozorov\Repositories\Tests\Support\WrongClass");
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_resolved_without_repository()
    {
        $resolver = new SelfResolver();

        $this->expectException(CouldNotResolve::class);

        $resolver->resolve(UserModel::class);
    }

    /**
     * @test
     */
    public function repository_is_resolved()
    {
        $resolver = new SelfResolver();

        $this->assertInstanceOf(CategoryRepository::class, $resolver->resolve(CategoryModel::class));
    }
}
