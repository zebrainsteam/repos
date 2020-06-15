<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

class Parameters
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
     * @param  bool  $countTotal
     *
     * @return  self
     */ 
    public function countTotal(bool $countTotal)
    {
        $this->countTotal = $countTotal;

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
     * @param  bool  $withMeta
     *
     * @return  self
     */ 
    public function withMeta(bool $withMeta)
    {
        $this->withMeta = $withMeta;

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
    public function setSelect($select)
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
    public function setOffset(int $offset)
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
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
