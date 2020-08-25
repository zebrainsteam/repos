<?php

namespace Repositories\Core\Contracts;

interface HasRepositoryInterface
{
    /**
     * @return RepositoryInterface
     */
    public static function getRepository(): RepositoryInterface;
}
