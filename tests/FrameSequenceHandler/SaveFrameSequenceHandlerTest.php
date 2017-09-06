<?php

namespace ITS\Countdown\GIF\FrameSequenceHandler;

use ITS\Countdown\GIF\FrameSequenceBuilder\DateTimeFrameSequenceBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers SaveFrameSequenceHandler
 * @uses   DateTimeFrameSequenceBuilder
 */
class SaveFrameSequenceHandlerTest extends TestCase
{
    /**
     * @var string
     */
    protected $tmpFile = __DIR__ . '/../fixtures/tmp.gif';

    /**
     * @dataProvider dataConstructor
     *
     * @param string|null $path
     */
    public function testConstructor($path = null)
    {
        $handler = new SaveFrameSequenceHandler($path);

        $this->assertAttributeSame($path, 'path', $handler);
    }

    /**
     * @dataProvider dataProcess
     *
     * @param \Iterator   $sequence
     * @param string      $expected_path
     */
    public function testProcess(\Iterator $sequence, $expected_path)
    {
        $handler = new SaveFrameSequenceHandler($this->tmpFile);

        $handler->process($sequence);

        $this->assertFileEquals($expected_path, $this->tmpFile);
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

    /**
     * @return \Iterator
     */
    public function dataConstructor()
    {
        yield [null];
        yield [$this->tmpFile];
    }

    /**
     * @return \Iterator
     */
    public function dataProcess()
    {
        $builder = new DateTimeFrameSequenceBuilder(
            file_get_contents(__DIR__ . '/../fixtures/frames.svg'),
            new \DateTime('1970-01-01 12:34:56'),
            new \DateTime('1970-01-02 00:00:00'),
            10
        );

        yield [$builder->getSequence(), __DIR__ . '/../fixtures/frames.gif'];
    }
}
