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
    /**
     * @return void
     */
    public function testInvoke()
    {
        $frame = new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/frame.svg'), 45296.0, 1.0);

        $middleware = new SvgToGifFrameMiddleware();

        $frame = $middleware($frame);

        $assertedImagick = new \Imagick(__DIR__ . '/../fixtures/frame.gif');
        $assertedImagick->resetIterator();
        $assertedImagick = $assertedImagick->appendImages(true);
        $testImagick = new \Imagick();
        $testImagick->readImageBlob($frame->getContent());
        $testImagick->resetIterator();
        $testImagick = $testImagick->appendImages(true);

        $diff = $assertedImagick->compareImages($testImagick, 1);
        $this->assertSame(0.0, $diff[1]);
    }
}