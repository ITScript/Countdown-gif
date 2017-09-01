<?php

namespace ITS\Countdown\GIF;

interface FrameSequenceHandler
{
    /**
     * @param \Iterator|Frame[] $sequence
     */
    public function process(\Iterator $sequence);
}