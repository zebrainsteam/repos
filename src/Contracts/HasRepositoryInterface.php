<?php

namespace Repositories\Contracts;

interface HasRepositoryInterface
{
    /**
     * @return RepositoryInterface
     */
    public static function getRepository(): RepositoryInterface;
}
