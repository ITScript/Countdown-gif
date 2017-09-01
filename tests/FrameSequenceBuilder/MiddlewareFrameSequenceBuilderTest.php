<?php

namespace ITS\Countdown\GIF\FrameSequenceBuilder;

use ITS\Countdown\GIF\Frame\FrameInstance;
use ITS\Countdown\GIF\FrameMiddleware\CompositeFrameMiddleware;
use ITS\Countdown\GIF\FrameMiddleware\InjectVariableFrameMiddleware;
use ITS\Countdown\GIF\FrameMiddleware\SvgToGifFrameMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * @covers MiddlewareFrameSequenceBuilder
 * @uses   FrameInstance
 * @uses   CompositeFrameMiddleware
 * @uses   InjectVariableFrameMiddleware
 * @uses   SvgToGifFrameMiddleware
 */
class MiddlewareFrameSequenceBuilderTest extends TestCase
{
    public function testInvoke()
    {
        $sequence = [
            new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/template.svg'), 45296.0, 1.0),
            new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/template.svg'), 45296.0, 2.0)
        ];

        $middleware = new CompositeFrameMiddleware(
            new InjectVariableFrameMiddleware(),
            new SvgToGifFrameMiddleware()
        );

        $builder = new MiddlewareFrameSequenceBuilder(new \ArrayIterator($sequence), $middleware);

        foreach ($builder->getSequence() as $frame) {
            $this->assertStringEqualsFile(__DIR__ . '/../fixtures/frame.gif', $frame->getContent());
        }

        $this->assertEquals(count($sequence), iterator_count($builder->getSequence()));
    }
}
