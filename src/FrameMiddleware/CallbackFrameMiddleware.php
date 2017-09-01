<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\FrameMiddleware;

class CallbackFrameMiddleware implements FrameMiddleware
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * CallbackFrameMiddleware constructor.
     * @param callable $callback
     */
    public function __construct(callable $callback = null)
    {
        $this->callback = $callback ?: function (Frame $frame, callable $next = null) {
            return is_callable($next) ? $next($frame) : $frame;
        };
    }

    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null)
    {
        return call_user_func($this->callback, $frame, $next);
    }
}