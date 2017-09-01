<?php

namespace ITS\Countdown\GIF\FrameSequenceBuilder;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\FrameMiddleware;
use ITS\Countdown\GIF\FrameSequenceBuilder;

class MiddlewareFrameSequenceBuilder implements FrameSequenceBuilder
{
    /**
     * @var FrameMiddleware
     */
    protected $middleware;

    /**
     * @var \Iterator|Frame[]
     */
    protected $sequence;

    /**
     * MiddlewareFrameSequenceBuilder constructor.
     * @param \Iterator|Frame[] $sequence
     * @param FrameMiddleware   $middleware
     */
    public function __construct(\Iterator $sequence, FrameMiddleware $middleware)
    {
        $this->middleware = $middleware;
        $this->sequence   = $sequence;
    }

    /**
     * @return \Iterator|Frame[]
     */
    public function getSequence()
    {
        foreach ($this->sequence as $index => $frame) {
            yield $index => call_user_func($this->middleware, $frame);
        }
    }
}