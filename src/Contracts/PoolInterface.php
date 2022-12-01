<?php

declare(strict_types=1);

namespace PCore\Pool\Contracts;

/**
 * Class PoolInterface
 * @package PCore\Pool\Contracts
 * @github https://github.com/pcore-framework/pool
 */
interface PoolInterface
{

    /**
     * @return mixed
     */
    public function open();

    /**
     * @return mixed
     */
    public function close();

    /**
     * @return mixed
     */
    public function get();

    /**
     * @return int
     */
    public function getPoolCapacity(): int;

    /**
     * @param $poolItem
     * @return mixed
     */
    public function release($poolItem);

    /**
     * @param $poolItem
     * @return mixed
     */
    public function discard($poolItem);

    /**
     * @return mixed
     */
    public function newPoolItem();

}
