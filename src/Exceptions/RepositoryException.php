<?php

declare(strict_types=1);

namespace Repositories\Exceptions;

use InvalidArgumentException;

class RepositoryException extends InvalidArgumentException
{
    /**
     * @var string $repositoryCode
     */
    protected $repositoryCode;

    /**
     * setRepositoryCode.
     *
     * @access	public
     * @param	string	$code	
     * @return	void
     */
    public function setRepositoryCode(string $code): void
    {
        $this->repositoryCode = $code;
    }

    /**
     * Returns repository code.
     *
     * @access	public
     * @return	string
     */
    public function getRepositoryCode(): string
    {
        return (string) $this->repositoryCode;
    }
}
