<?php

namespace ITS\Countdown\GIF;

interface FrameSequenceBuilder
{
    /**
     * @return \Iterator|Frame[]
     */
    public function getSequence();
}