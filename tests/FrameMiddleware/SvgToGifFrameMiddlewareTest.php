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
     * @var string
     */
    protected $tmpFile = __DIR__ . '/../fixtures/tmp.gif';

    /**
     * @return void
     */
    public function testInvoke()
    {
        $frame = new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/frame.svg'), 45296.0, 1.0);

        $middleware = new SvgToGifFrameMiddleware();

        $frame = $middleware($frame);

        file_put_contents($this->tmpFile, $frame->getContent());

        $r = (new \Imagick(__DIR__ . '/../fixtures/frame.gif'))->compareImages((new \Imagick($this->tmpFile)), \Imagick::METRIC_UNDEFINED);

        $this->assertGreaterThanOrEqual(0.9, $r[1]);
    }

    /**
     * @return void
     */
    public function setUp()
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }

        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }

        parent::tearDown();
    }
}