<?php

declare(strict_types=1);

namespace Repositories\Core\Contracts;

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
