<?php

namespace ITS\Countdown\GIF;

interface FrameMiddleware
{
    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null);
}