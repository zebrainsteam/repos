<?php

declare(strict_types=1);

namespace Prozorov\Repositories\Traits;

use Prozorov\Repositories\Query;

trait HasMeta
{
    /**
     * Calculate meta information
     * 
     * @access	protected
     * @param	Query	$query
     * @return	array|null
     */
    protected function getMeta(Query $query): ?array
    {
        if ($query->isWithMeta()) {
            $meta = [
                'offset' => $query->getOffset(),
                'limit' => $query->getLimit(),
            ];

            if ($query->isCountTotal()) {
                $meta['total'] = $this->count($query->getWhere() ?? []);
            }
        }

        return $meta ?? null;
    }
}
