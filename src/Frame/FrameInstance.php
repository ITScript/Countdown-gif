<?php

namespace ITS\Countdown\GIF\Frame;

use ITS\Countdown\GIF\Frame;

class FrameInstance implements Frame
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var float
     */
    protected $interval;

    /**
     * @var float
     */
    protected $duration;

    /**
     * FrameInstance constructor.
     * @param string $content
     * @param float  $interval
     * @param float  $duration
     */
    public function __construct($content, $interval, $duration)
    {
        $this->content  = $content;
        $this->interval = $interval;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return float
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }
}