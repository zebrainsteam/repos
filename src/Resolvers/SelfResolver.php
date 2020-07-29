<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Resolvers;

use Prozorov\Repositories\Contracts\{HasRepository, RepositoryInterface, ResolverInterface};
use Prozorov\Repositories\Exceptions\CouldNotResolve;

class SelfResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        if (class_exists($className)) {
            $model = new $className();
            if ($model instanceof hasRepository) {
                return $model->getRepository();
            }
        }

        $exception = new CouldNotResolve();
        $exception->setRepositoryCode($className);

        throw $exception;
    }
}
