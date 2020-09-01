<?php

namespace Repositories\Core\Tests\Support;

use Psr\Container\{ContainerInterface, ContainerExceptionInterface};
use Exception;

class SimpleContainer implements ContainerInterface
{
    public $resolved = [];

    public function get($id)
    {
        if (empty($this->resolved[$id])) {
            throw new class extends Exception implements ContainerExceptionInterface {

            };
        }

        return $this->resolved[$id];
    }

    public function has($id)
    {
        return ! empty($this->resolved[$id]);
    }
}
