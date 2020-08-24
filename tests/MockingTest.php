<?php

namespace Prozorov\Repositories\Tests;

class MockingTest extends BaseTestCase
{
    /**
     * @test
     */
    public function factory_allows_repository_mocking()
    {
        $expected = [
            'id' => 2,
            'first_name' => 'Petr',
            'last_name' => 'Petrov',
        ];

        $filter = ['id' => 2];

        $mock = $this->factory->mock('users');

        $mock->shouldReceive('first')
            ->once()
            ->with($filter)
            ->andReturn($expected);

        $user = $this->factory->getRepository('users')->first($filter);

        $this->assertEquals($expected, $user);
    }
}
