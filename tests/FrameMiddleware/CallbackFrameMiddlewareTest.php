<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use PHPUnit\Framework\TestCase;

/**
 * @covers CallbackFrameMiddleware
 * @uses   FrameInstance
 */
class CallbackFrameMiddlewareTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     * @param callable $callback
     */
    public function testConstructor(callable $callback = null)
    {
        $middleware = new CallbackFrameMiddleware($callback);

        $this->assertAttributeInternalType('callable', 'callback', $middleware);
    }

    /**
     * @return void
     */
    public function testInvokeSame()
    {
        $frame = new FrameInstance('test', 1.0, 1.0);

        $callback = function (Frame $frame, callable $next = null) {
            if (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        };

        $this->assertSame($frame, $callback($frame));
        $this->assertSame($frame, call_user_func(new CallbackFrameMiddleware($callback), $frame));
    }

    /**
     * @return void
     */
    public function testInvokeModify()
    {
        $frame = new FrameInstance('test', 1.0, 1.0);

        $callback = function (Frame $frame, callable $next = null) {
            $frame = new FrameInstance('test', 1.0, 1.0);

            if (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        };

        $this->assertNotSame($frame, $callback($frame));
        $this->assertNotSame($frame, call_user_func(new CallbackFrameMiddleware($callback), $frame));
    }

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        yield [null];
        yield [function (Frame $frame, callable $next = null) {return $frame;}];
    }
}
