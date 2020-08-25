<?php

declare(strict_types=1);

namespace Repositories\Core;

use Repositories\Core\Contracts\RepositoryInterface;
use Repositories\Core\{Query, Result};
use Repositories\Core\Exceptions\DataNotFound;
use Repositories\Core\Exceptions\NotImplemented;

abstract class AbstractRepository extends FixturableRepository
{
    abstract protected function doGet($params): ?iterable;

    abstract protected function doFirst(array $filter);

    abstract protected function doGetById(int $id, array $select = null);

    abstract protected function doGetByIdOrFail(int $id, array $select = null);

    abstract protected function doCreate(array $data);

    abstract protected function doUpdate($model, array $data): void;

    abstract protected function doDelete($model): void;

    abstract protected function doExists(array $filter): bool;

    abstract protected function doCount(array $filter = []): int;

    abstract protected function doOpenTransaction(): void;

    abstract protected function doCommitTransaction(): void;

    abstract protected function doRollbackTransaction(): void;
}
