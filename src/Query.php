<?php

declare(strict_types=1);

namespace Repositories\Core;

class Query
{
    /**
     * @var integer $limit
     */
    protected $limit = 20;

    /**
     * @var integer $offset
     */
    protected $offset = 0;

    /**
     * @var mixed $select
     */
    protected $select = null;

    /**
     * @var bool $withMeta
     */
    protected $withMeta = false;

    /**
     * @var bool $countTotal
     */
    protected $countTotal = false;

    /**
     * @var array $orderBy
     */
    protected $orderBy;

    /**
     * @var array $filter
     */
    protected $filter;

    /**
     * Get $countTotal
     *
     * @return  bool
     */ 
    public function isCountTotal(): bool
    {
        return $this->countTotal;
    }

    /**
     * Set $countTotal
     *
     * @return  self
     */ 
    public function countTotal()
    {
        $this->countTotal = true;

        return $this;
    }

    /**
     * Get $withMeta
     *
     * @return  bool
     */ 
    public function isWithMeta()
    {
        return $this->withMeta;
    }

    /**
     * Set $withMeta
     *
     * @return  self
     */ 
    public function withMeta()
    {
        $this->withMeta = true;

        return $this;
    }

    /**
     * Get $select
     *
     * @return  mixed
     */ 
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * Set $select
     *
     * @param  mixed  $select
     *
     * @return  self
     */ 
    public function select($select)
    {
        $this->select = $select;

        return $this;
    }

    /**
     * Get $offset
     *
     * @return  integer
     */ 
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Set $offset
     *
     * @param  integer  $offset
     *
     * @return  self
     */ 
    public function offset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get $limit
     *
     * @return  integer
     */ 
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Set $limit
     *
     * @param  integer  $limit
     *
     * @return  self
     */ 
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get $orderBy
     *
     * @return  array
     */ 
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set $orderBy
     *
     * @param  array  $orderBy  $orderBy
     *
     * @return  self
     */ 
    public function orderBy(array $orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get $filter
     *
     * @return  array
     */ 
    public function getWhere()
    {
        return $this->filter;
    }

    /**
     * Set $filter
     *
     * @param  array  $filter  $filter
     *
     * @return  self
     */ 
    public function where(array $filter)
    {
        $this->filter = $filter;

        return $this;
    }
}
