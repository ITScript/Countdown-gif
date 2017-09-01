<?php

namespace ITS\Countdown\GIF;

interface Frame
{
    /**
     * @return string
     */
    public function getContent();

    /**
     * @return float
     */
    public function getInterval();

    /**
     * @return float
     */
    public function getDuration();
}