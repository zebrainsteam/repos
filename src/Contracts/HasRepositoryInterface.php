<?php

namespace Prozorov\Repositories\Contracts;

interface HasRepositoryInterface
{
    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;
}
