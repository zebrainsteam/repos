<?php

namespace Repositories\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\{Query, RepositoryFactory};
use Repositories\Core\Resolvers\HardResolver;
use Repositories\Core\Tests\Support\FixturableProductRepository;

class FakableTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function repository_is_mocked_correct()
    {
        $expected = [
            [
                'id' => 2,
                'name' => 'product1',
                'price' => 100,
            ],
        ];

        $faked = [
            [
                'id' => 70,
                'name' => 'product70',
                'price' => 700,
            ],
        ];

        $factory = new RepositoryFactory(new HardResolver, [
            'products' => function () use ($expected) {
                return new FixturableProductRepository($expected);
            }
        ]);

        $repo = $factory->getRepository('products');

        $this->assertEquals($expected, $repo->get(new Query()));

        $factory->loadFixtures('products', $faked);

        $repo = $factory->getRepository('products');

        $this->assertEquals($faked, $repo->get(new Query()));
        $this->assertEquals($faked[0], $repo->getById(70));
    }
}
