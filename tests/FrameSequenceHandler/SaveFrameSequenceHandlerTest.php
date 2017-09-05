<?php

namespace ITS\Countdown\GIF\FrameSequenceHandler;

use ITS\Countdown\GIF\FrameSequenceBuilder\DateTimeFrameSequenceBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers SaveFrameSequenceHandler
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

        $r = (new \Imagick($expected_path))->compareImages((new \Imagick($this->tmpFile)), \Imagick::METRIC_UNDEFINED);

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
            file_get_contents(__DIR__ . '/../fixtures/countdown.svg'),
            new \DateTime('1970-01-01 12:34:56'),
            new \DateTime('1970-01-02 00:00:00'),
            10
        );

        yield [$builder->getSequence(), __DIR__ . '/../fixtures/countdown.gif'];
    }
}
