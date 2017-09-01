<?php

namespace ITS\Countdown\GIF\FrameSequenceHandler;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\FrameSequenceHandler;

class SaveFrameSequenceHandler implements FrameSequenceHandler
{
    /**
     * @var string|null
     */
    protected $path;

    /**
     * SVGFrameSequenceHandler constructor.
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;
    }

    /**
     * @param \Iterator|Frame[] $sequence
     */
    public function process(\Iterator $sequence)
    {
        $gif = new \Imagick();
        $gif->setFormat('gif');

        foreach ($sequence as $frame) {
            $gifFrame = new \Imagick();
            $gifFrame->readImageBlob($frame->getContent());
            $gifFrame->setImageDelay(intval($frame->getDuration()) * 100);

            $gif->addImage($gifFrame);
        }

        if ($this->path) {
            $gif->writeImages($this->path, true);
        } else {
            echo $gif->getImagesBlob();
        }

        $gif->clear();
        $gif->destroy();
    }
}