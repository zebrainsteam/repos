<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

use Prozorov\Repositories\Contracts\RepositoryInterface;
use Prozorov\Repositories\{Query, Result};
use Prozorov\Repositories\Exceptions\DataNotFound;
use Prozorov\Repositories\Exceptions\NotImplemented;

abstract class AbstractRepository extends FakableRepository
{
    /**
     * Creates data
     * 
     * @var array $data
     */
    abstract protected function doCreate(array $data);

    /**
     * Updates the provided model with provided data
     * 
     * @var mixed $model
     * @var array $data
     */
    abstract protected function doUpdate($model, array $data): void;

    /**
     * Deletes provided record
     * 
     * @var int $id
     */
    abstract protected function doDelete(int $id): void;

    /**
     * Returns count of requested data
     * 
     * @var array $filter
     */
    abstract protected function doCount(array $filter = []): int;

    /**
     * Returns true if requested data exists in the repository
     * 
     * @var array $filter
     */
    abstract protected function doExists(array $filter): bool;

    /**
     * Returns iterable or null if nothing is found
     * 
     * @var Query $query
     */
    abstract protected function doGet(Query $query);
}
