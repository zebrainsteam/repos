<?php

namespace Repositories\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\{ArrayRepository, Query};
use Repositories\Core\Exceptions\{DataNotFound, InvalidDataType, RepositoryException};

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

        $repo->update(1, ['position' => 'president']);

        $this->assertEquals($this->getUpdatedData(), $repo->getById(1));
    }

    /**
     * @test
     */
    public function transaction_rolls_back()
    {
        $repo = new ArrayRepository($this->getData());

        $repo->openTransaction();

        $repo->update(1, ['position' => 'president']);

        $this->assertEquals($this->getUpdatedData(), $repo->getById(1));

        $repo->rollbackTransaction();

        $this->assertEquals($this->getData()[0], $repo->getById(1));
    }

    /**
     * @test
     */
    public function transaction_is_committed()
    {
        $repo = new ArrayRepository($this->getData());

        $repo->openTransaction();

        $repo->update(1, ['position' => 'president']);

        $repo->commitTransaction();

        $this->assertEquals($this->getUpdatedData(), $repo->getById(1));
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
     * @test
     */
    public function transaction_is_commited()
    {
        $repo = new ArrayRepository($this->getData());

        $repo->openTransaction();

        $expected = $this->getUpdatedData();

        $repo->update(1, ['position' => $expected['position']]);

        $this->assertEquals($expected, $repo->getById(1));
    }

    /**
     * @test
     */
    public function primary_key_can_be_set()
    {
        $repo = new ArrayRepository($this->getData(), 'first_name');

        $this->assertEquals($this->getData()[0], $repo->getById('Ivan'));
        $this->assertEquals($this->getData()[1], $repo->getById('Petr'));
    }

    /**
     * @test
     */
    public function data_is_inserted()
    {
        $repo = new ArrayRepository($this->getData());

        $insertion = $this->getDataForInsertion();

        $repo->insert($insertion);

        $this->assertEquals(5, $repo->count());
        $this->assertEquals($insertion[0], $repo->getById(5));
        $this->assertEquals($insertion[1], $repo->getById(6));
    }

    /**
     * @test
     */
    public function data_is_inserted_with_transaction()
    {
        $repo = new ArrayRepository($this->getData());

        $repo->insertWithTransaction($this->getDataForInsertion());

        $this->assertEquals(5, $repo->count());
    }

    /**
     * @test
     */
    public function insert_with_transaction_rolls_back_data_in_case_of_error()
    {
        $repo = \Mockery::mock(ArrayRepository::class);

        $repo->makePartial();

        $repo->init($this->getData());
        
        $repo->shouldReceive('create')
            ->andThrow(RepositoryException::class, 'Some error occured');

        $insertion = $this->getDataForInsertion();

        try {
            $repo->insertWithTransaction($insertion);
        } catch (RepositoryException $exception) {
            $this->assertEquals(3, $repo->count());

            return;
        }

        $this->fail();
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

    /**
     * getUpdatedData.
     *
     * @access	protected
     * @return	array
     */
    protected function getUpdatedData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
            'position' => 'president',
        ];
    }

    /**
     * getDataForInsertion.
     *
     * @access	protected
     * @return	array
     */
    protected function getDataForInsertion(): array
    {
        return [
            [
                'id' => 5,
                'first_name' => 'Andrey',
                'last_name' => 'Rodov',
                'position' => 'driver',
            ],
            [
                'id' => 6,
                'first_name' => 'Mikhail',
                'last_name' => 'Potapov',
                'position' => 'driver',
            ],
        ];
    }
}
