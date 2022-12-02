<?php

declare(strict_types=1);

namespace PCore\Pool;

use Exception;
use PCore\Pool\Contracts\{PoolInterface, PoolItemInterface};
use Throwable;

/**
 * Class BasePoolItem
 * @package PCore\Pool
 * @github https://github.com/pcore-framework/pool
 */
class BasePoolItem implements PoolItemInterface
{

    protected bool $failed = false;

    public function __construct(
        protected PoolInterface $pool,
        protected object        $object,
    )
    {
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Throwable
     */
    public function __call(string $name, array $arguments)
    {
        try {
            if ($this->failed) {
                throw new Exception('Объект недоступен');
            }
            return $this->object->{$name}(...$arguments);
        } catch (Throwable $e) {
            $this->failed = true;
            throw $e;
        }
    }

    public function __destruct()
    {
        if ($this->failed) {
            $this->pool->discard($this);
        } else {
            $this->pool->release($this);
        }
    }

}
