<?php

declare(strict_types=1);

namespace Repositories\Core\Helpers;

use Repositories\Core\Exceptions\InvalidDataType;

class DuckTyper
{
    /**
     * getId.
     *
     * @access	public static
     * @param	object	$potentialyHasId
     * @param	string	$idField Default: 'id'
     * @throws  InvalidDataType
     * @return	int
     */
    public static function getId(object $potentialyHasId, string $idField = 'id'): int
    {
        if (property_exists($potentialyHasId, $idField) && is_int($potentialyHasId->$idField)) {
            return $potentialyHasId->$idField;
        }

        $getterFunction = 'get' . ucfirst($idField);

        if (method_exists($potentialyHasId, $getterFunction) && is_int($potentialyHasId->$getterFunction())) {
            return $potentialyHasId->$getterFunction();
        }

        throw new InvalidDataType('Id could not be found in object');
    }
}
