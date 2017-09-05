<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame\FrameInstance;
use PHPUnit\Framework\TestCase;

/**
 * @covers SvgToGifFrameMiddleware
 * @uses   FrameInstance
 */
class SvgToGifFrameMiddlewareTest extends TestCase
{
    public function testInvoke()
    {
        $frame = new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/frame.svg'), 45296.0, 1.0);

        $middleware = new SvgToGifFrameMiddleware();

        $frame = $middleware($frame);

        $this->assertStringEqualsFile(__DIR__ . '/../fixtures/frame.gif', $frame->getContent());
    }
}