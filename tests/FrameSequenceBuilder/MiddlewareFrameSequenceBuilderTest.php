<?php

namespace ITS\Countdown\GIF\FrameSequenceBuilder;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use ITS\Countdown\GIF\FrameMiddleware\CallbackFrameMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * @covers MiddlewareFrameSequenceBuilder
 * @uses   FrameInstance
 * @uses   CallbackFrameMiddleware
 */
class MiddlewareFrameSequenceBuilderTest extends TestCase
{
    public function testInvoke()
    {
        $sequence = [
            new FrameInstance('0', 1.0, 1.0),
            new FrameInstance('0', 2.0, 1.0)
        ];

        $middleware = new CallbackFrameMiddleware(function (Frame $frame, callable $next = null) {
            $frame = new FrameInstance(
                number_format($frame->getInterval(), 1),
                $frame->getInterval(),
                $frame->getDuration()
            );

            if ($next) {
                return $next($frame);
            } else {
                return $frame;
            }
        });

        $builder = new MiddlewareFrameSequenceBuilder(new \ArrayIterator($sequence), $middleware);

        foreach ($builder->getSequence() as $frame) {
            $this->assertSame(number_format($frame->getInterval(), 1), $frame->getContent());
        }

        $this->assertEquals(count($sequence), iterator_count($builder->getSequence()));
    }
}
