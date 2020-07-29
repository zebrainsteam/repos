<?php

namespace Prozorov\Repositories\Contracts;

interface HasRepository
{
    /**
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;
}