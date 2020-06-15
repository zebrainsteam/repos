<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

class Result
{
    /**
     * @var array $meta
     */
    protected $meta;

    /**
     * @var iterable $data
     */
    protected $data;

    public function __construct(iterable $data, array $meta = null)
    {
        $this->data = $data;
        $this->meta = $meta;
    }

    /**
     * getData.
     *
     * @access	public
     * @return	iterable
     */
    public function getData(): iterable
    {
        return $this->data;
    }

    /**
     * getMeta.
     *
     * @access	public
     * @return	array|null
     */
    public function getMeta(): ?array
    {
        return $this->meta;
    }
}
