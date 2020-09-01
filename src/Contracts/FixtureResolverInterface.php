<?php

declare(strict_types=1);

namespace Repositories\Core\Contracts;

use Repositories\Core\ArrayRepository;

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
