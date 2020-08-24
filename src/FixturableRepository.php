<?php

declare(strict_types=1);

namespace Repositories;

use Repositories\Contracts\RepositoryInterface;
use Repositories\Exceptions\DataNotFound;
use Repositories\Exceptions\NotImplemented;

abstract class FixturableRepository implements RepositoryInterface
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

    /**
     * isFake.
     *
     * @access	protected
     * @return	bool
     */
    protected function isFake(): bool
    {
        // Fixtures mode is disabled by default. Please refer to the documentation
        return false;
    }

    /**
     * getFixtures.
     *
     * @access	protected
     * @return	ArrayRepository
     */
    protected function fixtures(): ArrayRepository
    {
        throw new RuntimeException('Fixtures mode is disabled. Please refer to the documentation');
    }

    /**
     * {@inheritDoc}
     */
    public function get($params): ?iterable
    {
        if ($this->isFake()) {
            return $this->fixtures()->get($params);
        }

        return $this->doGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function first(array $filter)
    {
        if ($this->isFake()) {
            return $this->fixtures()->first($filter);
        }

        return $this->doFirst($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id, array $select = null)
    {
        if ($this->isFake()) {
            return $this->fixtures()->getById($id, $select);
        }

        return $this->doGetById($id, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function getByIdOrFail(int $id, array $select = null)
    {
        if ($this->isFake()) {
            return $this->fixtures()->getByIdOrFail($id, $select);
        }

        return $this->doGetByIdOrFail($id, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data)
    {
        if ($this->isFake()) {
            return $this->fixtures()->create($data);
        }

        return $this->doCreate($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update($model, array $data): void
    {
        if ($this->isFake()) {
            $this->fixtures()->update($model, $data);

            return;
        }

        $this->doUpdate($model, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($model): void
    {
        if ($this->isFake()) {
            $this->fixtures()->delete($model);

            return;
        }

        $this->doDelete($model);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(array $filter): bool
    {
        if ($this->isFake()) {
            return $this->fixtures()->exists($filter);
        }

        return $this->doExists($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function count(array $filter = []): int
    {
        if ($this->isFake()) {
            return $this->fixtures()->count($filter);
        }

        return $this->doCount($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function openTransaction(): void
    {
        if ($this->isFake()) {
            $this->fixtures()->openTransaction();

            return;
        }

        $this->doOpenTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function commitTransaction(): void
    {
        if ($this->isFake()) {
            $this->fixtures()->commitTransaction();

            return;
        }

        $this->doCommitTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTransaction(): void
    {
        if ($this->isFake()) {
            $this->fixtures()->rollbackTransaction();

            return;
        }

        $this->doRollbackTransaction();
    }
}
