<?php

declare(strict_types=1);

namespace Repositories\Core\Resolvers;

use Repositories\Core\Contracts\{RepositoryInterface, ResolverInterface};
use Repositories\Core\Exceptions\CouldNotResolve;

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
