<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\FrameMiddleware;

class CompositeFrameMiddleware implements FrameMiddleware
{
    /**
     * @var \SplPriorityQueue
     */
    protected $middlewares;

    /**
     * @var int
     */
    protected $serial = PHP_INT_MAX;

    /**
     * CompositeFrameMiddleware constructor.
     * @param FrameMiddleware[] $middlewares
     */
    public function __construct(FrameMiddleware ...$middlewares)
    {
        $this->middlewares = new \SplPriorityQueue();

        array_map([$this, 'add'], $middlewares);
    }

    /**
     * @param \ITS\Countdown\GIF\FrameMiddleware $middleware
     * @param int                                $priority
     * @return $this
     */
    public function add(FrameMiddleware $middleware, $priority = 1)
    {
        $this->middlewares->insert($middleware, [$priority, $this->serial--]);

        return $this;
    }

    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null)
    {
        if (is_callable($next)) {
            $callback = $next;
            $next     = $this->getClosure(iterator_to_array(clone $this->middlewares));
        } else {
            $callback = $this->getClosure(iterator_to_array(clone $this->middlewares));
        }

        return $callback($frame, $next);
    }

    /**
     * @param array $middlewares
     * @return \Closure
     */
    protected function getClosure(array $middlewares)
    {
        return function (Frame $frame, callable $next = null) use ($middlewares) {
            if (is_null($next)) {
                $next = array_shift($middlewares);
            }

            if (is_callable($next)) {
                return call_user_func($next, $frame, $this->getClosure($middlewares));
            } else {
                return $frame;
            }
        };
    }
}