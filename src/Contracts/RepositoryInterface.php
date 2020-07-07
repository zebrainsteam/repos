<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * Get records
     *
     * @access	public
     * @param	mixed	$params	
     * @return	void
     */
    public function get($params): iterable;

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
     * @return	mixed
     */
    public function getById(int $id, array $select = null);

    /**
     * Get model or fail
     *
     * @access	public
     * @param	int  	$id    	
     * @param	array	$select	Default: null
     * @return	mixed
     */
    public function getByIdOrFail(int $id, array $select = null);

    /**
     * Create a new record
     *
     * @param array $data
     * @param array|null $guarded
     * @throws ???
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update model
     *
     * @param $model
     * @param array $data
     * @throws ???
     * @return void
     */
    public function update($model, array $data): void;

    /**
     * Delete record
     *
     * @access	public
     * @param	int	$id	
     * @throws ???
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

    /**
     * Open transaction
     *
     * @access	public
     * @throws  ???
     * @return	void
     */
    public function openTransaction(): void;

    /**
     * Commit transaction if one exists
     *
     * @access	public
     * @throws  ???
     * @return	void
     */
    public function commitTransaction(): void;

    /**
     * Rollback transaction if one exists
     *
     * @access	public
     * @throws  ???
     * @return	void
     */
    public function rollbackTransaction(): void;
}
