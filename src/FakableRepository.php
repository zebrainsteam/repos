<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

use Prozorov\Repositories\Contracts\RepositoryInterface;
use Prozorov\Repositories\{Parameters, Result};
use Prozorov\Repositories\Exceptions\DataNotFound;
use Prozorov\Repositories\Exceptions\NotImplemented;

abstract class FakableRepository implements RepositoryInterface
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
    abstract protected function doCount(array $filter): int;

    /**
     * Returns true if requested data exists in the repository
     * 
     * @var array $filter
     */
    abstract protected function doExists(array $filter): bool;

    /**
     * Returns iterable or null if nothing is found
     * 
     * @var array $filter
     * @var Parameters $params
     */
    abstract protected function doGet(array $filter, Parameters $params = null);

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        if ($this->isFake()) {
            return $this->fixtures()->create($data);
        }

        return $this->doCreate($filter);
    }

    /**
     * @inheritDoc
     */
    public function update($model, array $data): void
    {
        if ($this->isFake()) {
            $this->fixtures()->update($model, $data);
        }

        $this->doUpdate($model, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        if ($this->isFake()) {
            $this->fixtures()->delete($id);
        }

        $this->doDelete($id);
    }

    /**
     * @inheritDoc
     */
    public function exists(array $filter): bool
    {
        if ($this->isFake()) {
            return $this->fixtures()->exists($filter);
        }

        return $this->doExists($filter);
    }

    /**
     * @inheritDoc
     */
    public function count(array $filter = []): int
    {
        if ($this->isFake()) {
            return $this->fixtures()->count($filter);
        }

        return $this->doCount($filter);
    }

    /**
     * @inheritDoc
     */
    public function get(array $filter, Parameters $params = null): Result
    {
        if ($this->isFake()) {
            return $this->fixtures()->get($filter, $params);
        }

        return new Result(
            $this->getRaw($filter, $params),
            $this->getMeta($params)
        );
    }

    /**
     * @inheritDoc
     */
    public function first(array $filter)
    {
        $data = $this->getRaw($filter, (new Parameters())->setLimit(1));

        if (empty($data[0])) {
            return null;
        }

        return $data[0];
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, array $select = null)
    {
        $data = $this->getRaw(['id' => $id], (new Parameters())->setLimit(1)->setSelect($select));

        if (empty($data[0])) {
            return null;
        }

        return $data[0];
    }

    /**
     * @inheritDoc
     */
    public function getByIdOrFail(int $id, array $select = null)
    {
        $data = $this->getById($id, $select);

        if (empty($data)) {
            throw new DataNotFound();
        }

        return $data;
    }

    /**
     * Returns raw data
     *
     * @access	protected
     * @param	array     	$filter	
     * @param	Parameters	$params	Default: null
     * @return	iterable|null
     */
    protected function getRaw(array $filter, Parameters $params = null)
    {
        if ($this->isFake()) {
            return $this->fixtures()->get($filter, $params)->getData();
        }

        return $this->doGet($filter, $params);
    }

    /**
     * Calculate meta information
     * 
     * @access	protected
     * @param	Parameters	$params	Default: null
     * @return	array|null
     */
    protected function getMeta(Parameters $params = null): ?array
    {
        if (empty($params)) {
            return null;
        }

        if ($params->isWithMeta()) {
            $meta = [
                'offset' => $params->getOffset(),
                'limit' => $params->getLimit(),
            ];

            if ($params->isCountTotal()) {
                $meta['total'] = $this->count($filter);
            }
        }

        return $meta;
    }

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
}
