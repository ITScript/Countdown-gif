<?php

namespace ITS\Countdown\GIF\Frame;

use PHPUnit\Framework\TestCase;

/**
 * @covers FrameInstance
 */
class FrameInstanceTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     * @param string $content
     * @param float  $interval
     * @param float  $duration
     */
    public function testConstructor($content, $interval, $duration)
    {
        $frame = new FrameInstance($content, $interval, $duration);

        $this->assertSame($frame->getContent(), $content);
        $this->assertSame($frame->getInterval(), $interval);
        $this->assertSame($frame->getDuration(), $duration);
    }

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        yield ['content', 112314123123.0, 1.0];
    }
}
