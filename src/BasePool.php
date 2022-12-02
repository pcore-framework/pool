<?php

declare(strict_types=1);

namespace PCore\Pool;

use PCore\Pool\Contracts\PoolInterface;
use RuntimeException;
use SplQueue;


/**
 * Class BasePool
 * @package PCore\Pool
 * @github https://github.com/pcore-framework/pool
 */
abstract class BasePool implements PoolInterface
{

    /**
     * @var SplQueue
     */
    protected SplQueue $splQueue;
    /**
     * @var bool
     */
    protected bool $isOpen = false;
    /**
     * @var int
     */
    protected int $currentSize = 0;

    /**
     * @return mixed|void
     */
    public function open()
    {
        if (!$this->isOpen) {
            $this->splQueue = new SplQueue();
            $this->isOpen = true;
        } else {
            throw new RuntimeException('Pool открыт');
        }
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $this->isOpen();
        $isMaximum = $this->currentSize >= $this->getPoolCapacity();
        if ($this->splQueue->isEmpty() && $isMaximum) {
            throw new RuntimeException('Слишком много подключений');
        }
        if (!$isMaximum) {
            $this->splQueue->enqueue($this->newPoolItem());
            $this->currentSize++;
        }
        return $this->splQueue->dequeue();
    }

    /**
     * @return void
     */
    protected function isOpen()
    {
        if (!$this->isOpen) {
            throw new RuntimeException('Pool не открыт');
        }
    }

    /**
     * @param $poolItem
     * @return void
     */
    public function release($poolItem)
    {
        $this->isOpen();
        if ($this->splQueue->count() < $this->getPoolCapacity()) {
            $this->splQueue->enqueue($poolItem);
        }
    }

    /**
     * @param $poolItem
     * @return void
     */
    public function discard($poolItem)
    {
        $this->isOpen();
        $this->currentSize--;
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->isOpen();
        $this->splQueue = new SplQueue();
        $this->isOpen = false;
    }

}
