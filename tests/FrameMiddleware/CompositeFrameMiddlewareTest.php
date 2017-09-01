<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use PHPUnit\Framework\TestCase;

/**
 * @covers CompositeFrameMiddleware
 * @uses   CallbackFrameMiddleware
 */
class CompositeFrameMiddlewareTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     *
     * @param array $middlewares
     */
    public function testConstructor(array $middlewares)
    {
        $middleware = new CompositeFrameMiddleware(...$middlewares);

        $this->assertAttributeCount(count($middlewares), 'middlewares', $middleware);
    }

    /**
     * @return void
     */
    public function testInvoke()
    {
        $callback1 = function (Frame $frame, callable $next = null) {
            $frame = new FrameInstance($frame->getContent() . '1', $frame->getInterval(), $frame->getDuration());

            if (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        };

        $callback2 = function (Frame $frame, callable $next = null) {
            $frame = new FrameInstance($frame->getContent() . '2', $frame->getInterval(), $frame->getDuration());

            if (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        };

        $frame  = new FrameInstance('0', 1.0, 1.0);
        $expect = new FrameInstance('012', 1.0, 1.0);

        $this->assertEquals($expect, $callback1($frame, $callback2));

        $middleware1 = new CallbackFrameMiddleware($callback1);
        $middleware2 = new CallbackFrameMiddleware($callback2);

        $this->assertEquals($expect, $middleware1($frame, $middleware2));

        $middleware = new CompositeFrameMiddleware(
            new CallbackFrameMiddleware($callback1),
            new CallbackFrameMiddleware($callback2)
        );

        $this->assertEquals($expect, $middleware($frame));
    }

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        yield [[]];

        yield [[new CallbackFrameMiddleware(), new CallbackFrameMiddleware()]];
    }
}
