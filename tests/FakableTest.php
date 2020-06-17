<?php

namespace Prozorov\Repositories\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Prozorov\Repositories\{Query, RepositoryFactory};
use Prozorov\Repositories\Resolvers\HardResolver;
use Prozorov\Repositories\Tests\Support\FakableProductRepository;

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
                return new FakableProductRepository($expected);
            }
        ]);

        $repo = $factory->getRepository('products');

        $this->assertEquals($expected, $repo->get(new Query())->getData());

        $factory->loadFixtures('products', $faked);

        $repo = $factory->getRepository('products');

        $this->assertEquals($faked, $repo->get(new Query())->getData());
        $this->assertEquals($faked[0], $repo->getById(70));
    }
}
