<?php

namespace Prozorov\Repositories\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Prozorov\Repositories\ArrayRepository;
use Prozorov\Repositories\Exceptions\DataNotFound;

class ArrayRepositoryTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function exists_is_correct()
    {
        $repo = new ArrayRepository($this->getData());

        $this->assertTrue($repo->exists(['id' => 1]));
        $this->assertFalse($repo->exists(['id' => 4]));
    }

    /**
     * @test
     */
    public function get_is_correct()
    {
        $repo = new ArrayRepository($this->getData());

        $this->assertEquals(3, $repo->count());
        $this->assertEquals(2, $repo->count(['position' => 'manager']));
        $this->assertEquals(1, $repo->count([
            'position' => 'manager', 
            'first_name' => 'Petr'
        ]));
    }

    /**
     * @test
     */
    public function get_by_id()
    {
        $data = $this->getData();

        $repo = new ArrayRepository($data);

        $entry = $repo->getById(1);

        $this->assertEquals($data[0], $entry);
    }

    /**
     * @test
     */
    public function exception_is_thrown_by_getByIdOrFail()
    {
        $repo = new ArrayRepository($this->getData());

        $this->expectException(DataNotFound::class);

        $repo->getByIdOrFail(4);
    }

    /**
     * @test
     */
    public function push_works_correct()
    {
        $repo = new ArrayRepository($this->getData());

        $expected = [
            'id' => 7,
            'first_name' => 'Igor',
            'last_name' => 'Igorev',
            'position' => 'courier',
        ];

        $repo->create($expected);

        $this->assertEquals(4, $repo->count());
        $this->assertEquals($expected, $repo->getById(7));
    }

    /**
     * @test
     */
    public function update_works_correct()
    {
        $repo = new ArrayRepository($this->getData());

        $expected = [
            'id' => 1,
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'position' => 'president',
        ];

        $repo->update(1, ['position' => 'president']);

        $this->assertEquals($expected, $repo->getById(1));
    }

    /**
     * @test
     */
    public function delete_works_correct()
    {
        $repo = new ArrayRepository($this->getData());

        $repo->delete(1);

        $this->assertEquals(2, $repo->count());
        $this->assertEmpty($repo->getById(1));
    }

    /**
     * getData.
     *
     * @access	protected
     * @return	array
     */
    protected function getData(): array
    {
        return [
            [
                'id' => 1,
                'first_name' => 'Ivan',
                'last_name' => 'Ivanov',
                'position' => 'director',
            ],
            [
                'id' => 2,
                'first_name' => 'Petr',
                'last_name' => 'Petrov',
                'position' => 'manager',
            ],
            [
                'id' => 3,
                'first_name' => 'Sidr',
                'last_name' => 'Sidorov',
                'position' => 'manager',
            ],
        ];
    }
}
