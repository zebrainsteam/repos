<?php

declare(strict_types=1);

namespace Repositories\Core;

use Repositories\Core\Contracts\RepositoryInterface;
use Repositories\Core\Query;
use Repositories\Core\Exceptions\{DataNotFound, RepositoryException, InvalidDataType};
use Repositories\Core\Helpers\DuckTyper;

class ArrayRepository implements RepositoryInterface
{
    /**
     * @var array $data
     */
    protected $data;

    /**
     * @var string $idField
     */
    protected $idField;

    /**
     * @var array $dataCopy
     */
    protected $dataCopy;

    public function __construct(array $data, string $idField = 'id')
    {
        $this->init($data, $idField);
    }

    /**
     * Initializes repository
     * 
     * @access	public
     * @param	array 	$data
     * @param	string	$idField	Default: 'id'
     * @return	void
     */
    public function init(array $data, string $idField = 'id'): void
    {
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
            $currentKeys = array_keys($this->data);

            $data[$this->idField] = empty($currentKeys) ? 1 : (max($currentKeys) + 1);
        }

        $this->data[$data[$this->idField]] = $data;

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function update($model, array $data): void
    {
        $id = is_int($model) ? $model : DuckTyper::getId($model, $this->idField);

        $model = $this->data[$id] ?? null;

        $this->data[$model[$this->idField]] = array_merge($model, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete($model): void
    {
        $id = is_int($model) ? $model : DuckTyper::getId($model, $this->idField);

        unset($this->data[$id]);
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
        return $this->data[$id] ?? null;
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

        $this->dataCopy = $this->data;
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
     * @return	array|null
     */
    protected function getRaw(Query $query)
    {
        $data = $this->data;

        if (! empty($query->getWhere())) {
            foreach ($query->getWhere() as $key => $value) {
                $data = array_filter($this->data, function($item) use ($key, $value) {
                    return $item[$key] === $value;
                });
            }
        }

        if(empty($data)) {
            return null;
        }

        return array_values($data);
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
