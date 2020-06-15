<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Contracts;

use Prozorov\Repositories\ArrayRepository;

interface FixtureResolverInterface
{
    /**
     * getFixtures.
     *
     * @access	public
     * @param	mixed	$fixtures	
     * @return	ArrayRepository
     */
    public function getFixtures($fixtures): ArrayRepository;
}
