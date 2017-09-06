<?php

namespace Functional;

use ITS\Countdown\GIF\FrameMiddleware\CompositeFrameMiddleware;
use ITS\Countdown\GIF\FrameMiddleware\InjectVariableFrameMiddleware;
use ITS\Countdown\GIF\FrameMiddleware\SvgToGifFrameMiddleware;
use ITS\Countdown\GIF\FrameSequenceBuilder\DateTimeFrameSequenceBuilder;
use ITS\Countdown\GIF\FrameSequenceBuilder\MiddlewareFrameSequenceBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers CompositeFrameMiddleware
 * @covers InjectVariableFrameMiddleware
 * @covers SvgToGifFrameMiddleware
 * @covers DateTimeFrameSequenceBuilder
 * @covers MiddlewareFrameSequenceBuilder
 */
class CountdownTest extends TestCase
{
    /**
     * @var string
     */
    protected $tmpFile = __DIR__ . '/../fixtures/tmp.gif';

    /**
     * @return void
     */
    public function testCountdown()
    {
        $now = new \DateTimeImmutable('1970-01-01 00:00:00');
        $end = new \DateTimeImmutable('1970-01-01 12:34:56');

        $sequence = new DateTimeFrameSequenceBuilder(file_get_contents(__DIR__ . '/../fixtures/countdown.svg'), $now, $end);

        $middleware = new CompositeFrameMiddleware(
            new InjectVariableFrameMiddleware(),
            new SvgToGifFrameMiddleware()
        );

        $sequence = new MiddlewareFrameSequenceBuilder($sequence->getSequence(), $middleware);

        $handler = new \ITS\Countdown\GIF\FrameSequenceHandler\SaveFrameSequenceHandler($this->tmpFile);
        $handler->process($sequence->getSequence());

        $assertedImagick = new \Imagick(__DIR__ . '/../fixtures/countdown.gif');
        $assertedImagick->resetIterator();
        $assertedImagick = $assertedImagick->appendImages(true);
        $testImagick = new \Imagick($this->tmpFile);
        $testImagick->resetIterator();
        $testImagick = $testImagick->appendImages(true);

        $diff = $assertedImagick->compareImages($testImagick, 1);
        $this->assertSame(0.0, $diff[1]);
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