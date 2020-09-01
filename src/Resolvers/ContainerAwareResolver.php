<?php

declare(strict_types=1);

namespace Repositories\Core\Resolvers;

use Repositories\Core\Contracts\{RepositoryInterface, ResolverInterface};
use Repositories\Core\Exceptions\CouldNotResolve;
use Psr\Container\{ContainerInterface, ContainerExceptionInterface};

class ContainerAwareResolver implements ResolverInterface
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        try {
            $resolved = $this->container->get($className);

            if (! ($resolved instanceof RepositoryInterface)) {
                $class = get_class($resolved);

                $message = $class . ' does not implements RepositoryInterface. Refer to documentation';
                $this->fail($className, $message);
            }

            return $resolved;
        } catch (ContainerExceptionInterface $exception) {
            $this->fail($className, $exception->getMessage());
        }
    }

    /**
     * fail.
     *
     * @access    protected
     * @param string $className
     * @param string $message
     * @return    void
     */
    protected function fail(string $className, string $message = ''): void
    {
        $exception = new CouldNotResolve($message);
        $exception->setRepositoryCode($className);

        throw $exception;
    }
}
