<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Resolvers;

use Prozorov\Repositories\Contracts\{RepositoryInterface, ResolverInterface};
use Prozorov\Repositories\Exceptions\CouldNotResolve;

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
