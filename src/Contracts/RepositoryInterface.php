<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Contracts;

use Prozorov\Repositories\{Query, Result};

interface RepositoryInterface
{
    /**
     * Get records
     *
     * @access	public
     * @param	Query	$params	
     * @return	void
     */
    public function get(Query $query): Result;

    /**
     * Get first record
     *
     * @access	public
     * @param	array	$filter	
     * @return	mixed
     */
    public function first(array $filter);

    /**
     * Get model by id
     *
     * @access	public
     * @param	int  	$id    	
     * @param	array	$select	Default: null
     * @return	void
     */
    public function getById(int $id, array $select = null);

    /**
     * Get model or fail
     *
     * @access	public
     * @param	int  	$id    	
     * @param	array	$select	Default: null
     * @return	void
     */
    public function getByIdOrFail(int $id, array $select = null);

    /**
     * Create a new record
     *
     * @param array $data
     * @param array|null $guarded
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update model
     *
     * @param $model
     * @param array $data
     * @return void
     */
    public function update($model, array $data): void;

    /**
     * Delete record
     *
     * @access	public
     * @param	int	$id	
     * @return	void
     */
    public function delete(int $id): void;

    /**
     * Check record is exist
     *
     * @param array $filter
     * @return bool
     */
    public function exists(array $filter): bool;

    /**
     * Count records by parameters
     *
     * @access	public
     * @param	array	$filter	
     * @return	int
     */
    public function count(array $filter = []): int;
}
