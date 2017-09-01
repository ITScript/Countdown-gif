<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use ITS\Countdown\GIF\FrameMiddleware;

class SvgToGifFrameMiddleware implements FrameMiddleware
{
    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null)
    {
        $gif = new \Imagick();
        $gif->readImageBlob($frame->getContent());
        $gif->setFormat('gif');

        $frame = new FrameInstance($gif->getImageBlob(), $frame->getInterval(), $frame->getDuration());

        if (is_callable($next)) {
            return $next($frame);
        }

        return $frame;
    }
}