<?php

declare(strict_types=1);

namespace Repositories\Core;

use Repositories\Core\Contracts\RepositoryInterface;
use Repositories\Core\Query;
use Repositories\Core\Exceptions\{DataNotFound, RepositoryException, InvalidDataType};
use Repositories\Core\Helpers\DuckTyper;
use Illuminate\Support\Collection;

class ArrayRepository implements RepositoryInterface
{
    /**
     * @var Collection $data
     */
    protected $data;

    /**
     * @var string $idField
     */
    protected $idField;

    /**
     * @var Collection $dataCopy
     */
    protected $dataCopy;

    public function __construct(array $data, string $idField = 'id')
    {
        $this->data = new Collection();

        $this->idField = $idField;

        foreach ($data as $entry) {
            $this->create($entry);
        }
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        if (empty($data[$this->idField])) {
            throw new InvalidDataType('Data set does not have a primary key ' . $this->idField);
        }

        $this->data->put($data[$this->idField], $data);
    }

    /**
     * @inheritDoc
     */
    public function update($model, array $data): void
    {
        $id = is_int($model) ? $model : DuckTyper::getId($model, $this->idField);

        $model = $this->data->get($id);

        $this->data->put($model[$this->idField], array_merge($model, $data));
    }

    /**
     * @inheritDoc
     */
    public function delete($model): void
    {
        $id = is_int($model) ? $model : DuckTyper::getId($model, $this->idField);

        $this->data->forget($id);
    }

    /**
     * @inheritDoc
     */
    public function exists(array $filter): bool
    {
        $data = $this->getRaw((new Query())->where($filter));

        return ! empty($data);
    }

    /**
     * @inheritDoc
     */
    public function count(array $filter = []): int
    {
        $data = $this->getRaw((new Query())->where($filter));

        return count($data);
    }

    /**
     * @inheritDoc
     */
    public function get($params): ?iterable
    {
        return $this->getRaw($params);
    }

    /**
     * @inheritDoc
     */
    public function first(array $filter)
    {
        $data = $this->getRaw((new Query())->where($filter)->limit(1));

        if (empty($data[0])) {
            return null;
        }

        return $data[0];
    }

    /**
     * @inheritDoc
     */
    public function getById($id, array $select = null)
    {
        return $this->data->get($id);
    }

    /**
     * @inheritDoc
     */
    public function getByIdOrFail($id, array $select = null)
    {
        $data = $this->getById($id, $select);

        if (empty($data)) {
            throw new DataNotFound();
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function openTransaction(): void
    {
        if ($this->dataCopy !== null) {
            throw new RepositoryException('Array repository does not support nested transactions');
        }

        $this->dataCopy = clone $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function commitTransaction(): void
    {
        $this->assertTransactionIsOpened();

        unset($this->dataCopy);
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTransaction(): void
    {
        $this->assertTransactionIsOpened();

        $this->data = $this->dataCopy;

        unset($this->dataCopy);
    }

    /**
     * Returns raw data
     *
     * @access	protected
     * @param	Query	$query	
     * @return	iterable|null
     */
    protected function getRaw(Query $query)
    {
        $data = $this->data;

        if (! empty($query->getWhere())) {
            foreach ($query->getWhere() as $key => $value) {
                $data = $this->data->where($key, $value);
            }
        }

        return $data->values()->toArray();
    }

    /**
     * assertTransactionIsOpened.
     *
     * @access	protected
     * @return	void
     */
    protected function assertTransactionIsOpened(): void
    {
        if ($this->dataCopy === null) {
            throw new RepositoryException('No transaction has been opened');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function insert(iterable $data): void
    {
        foreach ($data as $entry) {
            $this->create($entry);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function insertWithTransaction(iterable $data): void
    {
        $this->openTransaction();

        try {
            $this->insert($data);
        } catch (RepositoryException $exception) {
            $this->rollbackTransaction();

            throw $exception;
        }

        $this->commitTransaction();
    }
}
