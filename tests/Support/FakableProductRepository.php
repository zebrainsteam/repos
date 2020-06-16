<?php

namespace Prozorov\Repositories\Tests\Support;

use Prozorov\Repositories\AbstractRepository;
use Prozorov\Repositories\Parameters;
use Prozorov\Repositories\Result;

class FakableProductRepository extends AbstractRepository
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

    protected function doDelete(int $id): void
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

    protected function doGet(array $filter, Parameters $params = null)
    {
        // No need to implement filtering
        return $this->data;
    }
}
