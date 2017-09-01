<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame\FrameInstance;
use PHPUnit\Framework\TestCase;

/**
 * @covers InjectVariableFrameMiddleware
 * @uses   FrameInstance
 */
class InjectVariableFrameMiddlewareTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     *
     * @param $substitutionGenerator
     */
    public function testConstructor($substitutionGenerator)
    {
        $middleware = new InjectVariableFrameMiddleware($substitutionGenerator);

        $this->assertAttributeNotEmpty('substitutionGenerator', $middleware);
    }

    /**
     * @return void
     */
    public function testInvoke()
    {
        $frame      = new FrameInstance(file_get_contents(__DIR__ . '/../fixtures/template.svg'), 45296.0, 1.0);
        $middleware = new InjectVariableFrameMiddleware();

        $this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/frame.svg', $middleware($frame)->getContent());
    }

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        yield [null];
    }
}
