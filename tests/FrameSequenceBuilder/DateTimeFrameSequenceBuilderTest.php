<?php

namespace ITS\Countdown\GIF\FrameSequenceBuilder;

use ITS\Countdown\GIF\Frame;
use PHPUnit\Framework\TestCase;

/**
 * @covers DateTimeFrameSequenceBuilder
 */
class DateTimeFrameSequenceBuilderTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     *
     * @param string             $template
     * @param \DateTimeInterface $now
     * @param \DateTimeInterface $end
     * @param int                $limit
     * @param int                $duration
     */
    public function testConstructor($template, \DateTimeInterface $now, \DateTimeInterface $end, $limit, $duration)
    {
        $builder = new DateTimeFrameSequenceBuilder($template, $now, $end, $limit, $duration);

        $this->assertAttributeInternalType('string', 'template', $builder);
        $this->assertAttributeInstanceOf(\DateTimeInterface::class, 'now', $builder);
        $this->assertAttributeInstanceOf(\DateTimeInterface::class, 'end', $builder);
        $this->assertAttributeInternalType('int', 'limit', $builder);
        $this->assertAttributeInternalType('int', 'duration', $builder);
    }

    /**
     * @depends      testConstructor
     * @dataProvider dataConstructor
     *
     * @param string             $template
     * @param \DateTimeInterface $now
     * @param \DateTimeInterface $end
     * @param int                $limit
     * @param int                $duration
     */
    public function testGetSequence($template, \DateTimeInterface $now, \DateTimeInterface $end, $limit, $duration)
    {
        $builder = new DateTimeFrameSequenceBuilder($template, $now, $end, $limit, $duration);

        $sequence = $builder->getSequence();

        $this->assertInstanceOf(\Iterator::class, $sequence);

        foreach ($sequence as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
            $this->assertInternalType('float', $frame->getInterval());
            $this->assertInternalType('float', $frame->getDuration());
            $this->assertNotEmpty($frame->getContent());
            $this->assertGreaterThanOrEqual(0.0, $frame->getInterval());
            $this->assertGreaterThanOrEqual(0.0, $frame->getDuration());
            $this->assertGreaterThanOrEqual(0, --$limit);
        }
    }

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        $template = \file_get_contents(__DIR__ . '/../fixtures/countdown.svg');

        yield [
            $template,
            new \DateTime('1970-01-01 00:00:00'),
            new \DateTime('1970-01-01 00:00:00'),
            10,
            1
        ];

        yield [
            $template,
            new \DateTime('1970-01-02 00:00:00'),
            new \DateTime('1970-01-01 00:00:00'),
            10,
            1
        ];

        yield [
            $template,
            new \DateTime('1970-01-01 00:00:00'),
            new \DateTime('1970-01-02 00:00:00'),
            10,
            1
        ];

        yield [
            $template,
            new \DateTime('1970-01-01 23:59:50'),
            new \DateTime('1970-01-02 00:00:00'),
            30,
            1
        ];
    }
}