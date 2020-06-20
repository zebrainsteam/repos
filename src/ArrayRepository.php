<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

use Prozorov\Repositories\Contracts\RepositoryInterface;
use Prozorov\Repositories\{Query, Result};
use Prozorov\Repositories\Exceptions\DataNotFound;
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

    public function __construct(array $data, string $idField = 'id')
    {
        $this->data = (new Collection($data))->keyBy('id');
        $this->idField = $idField;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        $this->data->put($data[$this->idField], $data);
    }

    /**
     * @inheritDoc
     */
    public function update($model, array $data): void
    {
        if (is_int($model)) {
            $model = $this->data->get($model);

            $this->data->put($model[$this->idField], array_merge($model, $data));
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
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
    public function get(Query $query): Result
    {
        return new Result(
            $this->getRaw($query),
            $this->getMeta($query)
        );
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
    public function getById(int $id, array $select = null)
    {
        return $this->data->get($id);
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
     * Calculate meta information
     * 
     * @access	protected
     * @param	Query	$query
     * @return	array|null
     */
    protected function getMeta(Query $query): ?array
    {
        if ($query->isWithMeta()) {
            $meta = [
                'offset' => $query->getOffset(),
                'limit' => $query->getLimit(),
            ];

            if ($query->isCountTotal()) {
                $meta['total'] = $this->count($query->getWhere() ?? []);
            }
        }

        return $meta ?? null;
    }
}
