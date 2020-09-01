<?php

declare(strict_types=1);

namespace Repositories\Core\Resolvers;

use Repositories\Core\Contracts\{HasRepositoryInterface, RepositoryInterface, ResolverInterface};
use Repositories\Core\Exceptions\CouldNotResolve;

class ExistingRepositoryResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        if (class_exists($className)) {
            if (in_array(HasRepositoryInterface::class, class_implements($className))) {
                return $className::getRepository();
            }
        }

        $exception = new CouldNotResolve();
        $exception->setRepositoryCode($className);

        throw $exception;
    }
}
