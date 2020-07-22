<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Resolvers;

use Prozorov\Repositories\Contracts\{RepositoryInterface, ResolverInterface};
use Prozorov\Repositories\Exceptions\CouldNotResolve;
use Psr\Container\{ContainerInterface, ContainerExceptionInterface};
use InvalidArgumentException;

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
                $this->fail('Container must return ');
            }
        } catch (ContainerExceptionInterface $exception) {
            $this->fail($exception->getMessage());
        }
    }

    /**
     * fail.
     *
     * @access	protected
     * @param	string	$className	
     * @return	void
     */
    protected function fail(string $className, string $message = ''): void
    {
        $exception = new CouldNotResolve($message);
        $exception->setRepositoryCode($className);

        throw $exception;
    }
}
