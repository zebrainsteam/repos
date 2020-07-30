<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Resolvers;

use Prozorov\Repositories\Contracts\{HasRepositoryInterface, RepositoryInterface, ResolverInterface};
use Prozorov\Repositories\Exceptions\CouldNotResolve;

class SelfResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        if (class_exists($className)) {
            if (in_array(HasRepositoryInterface::class, class_implements($className))) {
                return (new $className())->getRepository();
            }
        }

        $exception = new CouldNotResolve();
        $exception->setRepositoryCode($className);

        throw $exception;
    }
}
