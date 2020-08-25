<?php

declare(strict_types=1);

namespace Repositories\Core\Resolvers;

use Repositories\Core\Contracts\{RepositoryInterface, ResolverInterface};
use Repositories\Core\Exceptions\CouldNotResolve;

class ChainResolver implements ResolverInterface
{
    /**
     * @var array $chain
     */
    protected $chain;

    /**
     * @var array $resolvers
     */
    protected $resolvers;

    public function __construct(array $chain)
    {
        // todo: assert every element in $chain is a resolver
        $this->chain = $chain;
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $className): RepositoryInterface
    {
        foreach ($this->chain as $resolverName) {
            $resolver = $this->getResolver($resolverName);

            try {
                return $resolver->resolve($className);
            } catch (CouldNotResolve $exception) {
                continue;
            }
        }

        $exception = new CouldNotResolve();
        $exception->setRepositoryCode($className);

        throw $exception;
    }

    /**
     * Create resolver if needed and return it
     *
     * @access	protected
     * @param	string|ResolverInterface	$code	
     * @return	ResolverInterface
     */
    protected function getResolver($resolver): ResolverInterface
    {
        $resolverClass = is_object($resolver) ? get_class($resolver) : $resolver;

        if (empty($this->resolvers[$resolverClass])) {
            if (is_object($resolver)) {
                $this->resolvers[$resolverClass] = $resolver;
            } elseif (is_string($resolver) && class_exists($resolver)) {
                $this->resolvers[$resolverClass] = new $resolver;
            } else {
                throw new \InvalidArgumentException('Invalid resolver provided');
            }

        }
        
        return $this->resolvers[$resolverClass];
    }
}
