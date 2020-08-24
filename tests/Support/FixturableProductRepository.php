<?php

namespace Repositories\Tests\Support;

use Repositories\AbstractRepository;

class FixturableProductRepository extends AbstractRepository
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function doCreate(array $data)
    {
        $this->data[] = $data;
    }

    protected function doUpdate($model, array $data): void
    {
        // No need to implement
    }

    protected function doDelete($model): void
    {
        // No need to implement
    }

    protected function doCount(array $filter = []): int
    {
        return count($this->data);
    }

    protected function doExists(array $filter): bool
    {
        // No need to implement
        return false;
    }

    protected function doGet($params): ?iterable
    {
        // No need to implement filtering
        return $this->data;
    }




    protected function doFirst(array $filter)
    {
        // No need to implement
    }

    protected function doGetById(int $id, array $select = null)
    {
        // No need to implement
    }

    protected function doGetByIdOrFail(int $id, array $select = null)
    {
        // No need to implement
    }

    protected function doOpenTransaction(): void
    {
        // No need to implement
    }

    protected function doCommitTransaction(): void
    {
        // No need to implement
    }

    protected function doRollbackTransaction(): void
    {
        // No need to implement
    }
}
