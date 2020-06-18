<?php

declare(strict_types=1);

namespace Prozorov\Repositories;

use Prozorov\Repositories\Contracts\{FixtureResolverInterface, RepositoryInterface, ResolverInterface};
use Prozorov\Repositories\Exceptions\CouldNotResolve;
use Mockery;

class RepositoryFactory
{
    /**
     * @var array $bindings
     */
    protected $bindings = [];

    /**
     * @var array $resolved
     */
    protected $resolved = [];

    /**
     * @var ResolverInterface $resolver
     */
    protected $resolver;

    /**
     * @var string $fixtureResolverClass
     */
    protected $fixtureResolverClass;

    /**
     * @var string $fixtureResolver
     */
    protected $fixtureResolver;

    public function __construct(ResolverInterface $resolver, array $bindings, string $fixtureResolverClass = null)
    {
        $this->resolver = $resolver;
        $this->bindings = $bindings;

        if (! empty($fixtureResolverClass) && class_exists($fixtureResolverClass)) {
            $this->fixtureResolverClass = $fixtureResolverClass;
        } else {
            $this->fixtureResolverClass = ArrayFixtureResolver::class;
        }
    }

    /**
     * getRepository.
     *
     * @access	public
     * @param	string	$code
     * @return	RepositoryInterface
     */
    public function getRepository(string $code): RepositoryInterface
    {
        $solid = $this->getSolid($code);

        if (empty($this->resolved[$solid])) {
            $this->resolved[$solid] = $this->resolver->resolve($solid);
        }

        return $this->resolved[$solid];
    }

    /**
     * mock.
     *
     * @access	public
     * @param	string	$code	
     * @return	mixed
     */
    public function mock(string $code)
    {
        $solid = $this->getRepository($code);

        $className = get_class($solid);

        $this->reset($code);

        $solid = $this->getSolid($code);

        $mock = Mockery::mock($className)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->resolved[$solid] = $mock;

        return $this->resolved[$solid];
    }

    /**
     * loadFixtures.
     *
     * @access	public
     * @param	string	$code
     * @param	mixed 	$fixtures
     * @return	void
     */
    public function loadFixtures(string $code, $fixtures): void
    {
        $solid = $this->getSolid($code);

        $this->mock($code);

        // Assert $code is a FakableRepository

        $fixtures = $this->getFixtureResolver()->getFixtures($fixtures);

        $this->resolved[$solid]->shouldReceive('isFake')->andReturn(true);
        $this->resolved[$solid]->shouldReceive('fixtures')->andReturn($fixtures);
    }

    /**
     * Clears resolved instance
     *
     * @access	public
     * @param	string	$code
     * @return	void
     */
    public function reset(string $code): void
    {
        unset($this->resolved[$code]);
    }

    /**
     * Clears resolved instance
     *
     * @access	public
     * @return	void
     */
    public function resetAll(): void
    {
        foreach ($this->resolved as $repository) {
            unset($repository);
        }
    }

    /**
     * getResolvedInstance.
     *
     * @access	protected
     * @param	string	$code	
     * @return	string
     */
    protected function getSolid(string $code): string
    {
        $solid = $this->bindings[$code] ?? $code;

        if (is_callable($solid)) {
            return $this->resolveCallable($code, $solid);
        }

        return $solid;
    }

    /**
     * Resolves callable
     *
     * @access	protected
     * @param	string  	$code 	
     * @param	callable	$solid	
     * @return	string
     */
    protected function resolveCallable(string $code, callable $solid): string
    {
        if (empty($this->resolved[$code])) {
            $resolved = $solid();

            if (! ($resolved instanceof RepositoryInterface)) {
                $exception = new CouldNotResolve();
                $exception->setRepositoryCode($code);

                throw $exception;
            }

            $this->resolved[$code] = $solid();
        }

        return $code;
    }

    /**
     * getFixtureResolver.
     *
     * @access	protected
     * @return	FixtureResolverInterface
     */
    protected function getFixtureResolver(): FixtureResolverInterface
    {
        if (empty($this->fixtureResolver)) {
            $this->fixtureResolver = new $this->fixtureResolverClass;
        }

        // Assert $this->fixtureResolver implements FixtureResolverInterface

        return $this->fixtureResolver;
    }
}
