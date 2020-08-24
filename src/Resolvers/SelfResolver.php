<?php

declare(strict_types=1);

namespace Repositories\Resolvers;

use Repositories\Contracts\{HasRepositoryInterface, RepositoryInterface, ResolverInterface};
use Repositories\Exceptions\CouldNotResolve;

class SelfResolver implements ResolverInterface
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
