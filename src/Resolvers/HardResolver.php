<?php

declare(strict_types=1);

namespace Repositories\Resolvers;

use Repositories\Contracts\{RepositoryInterface, ResolverInterface};
use Repositories\Exceptions\CouldNotResolve;

class HardResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        if (! class_exists($className)) {
            $exception = new CouldNotResolve();
            $exception->setRepositoryCode($className);

            throw $exception;
        }

        return new $className;
    }
}
