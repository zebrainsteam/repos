<?php

declare(strict_types=1);

namespace Repositories\Contracts;

interface ResolverInterface
{
    /**
     * Resolves repository by code
     *
     * @access	public
     * @param	string	$className	
     * @return	RepositoryInterface
     */
    public function resolve(string $className): RepositoryInterface;
}
