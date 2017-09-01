<?php

namespace ITS\Countdown\GIF\FrameSequenceBuilder;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use ITS\Countdown\GIF\FrameSequenceBuilder;

class DateTimeFrameSequenceBuilder implements FrameSequenceBuilder
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var \DateTimeInterface
     */
    protected $now;

    /**
     * @var \DateTimeInterface
     */
    protected $end;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $duration;

    /**
     * FrameSequenceBuilderInstance constructor.
     * @param string             $template
     * @param \DateTimeInterface $now
     * @param \DateTimeInterface $end
     * @param int                $limit
     * @param int                $duration
     */
    public function __construct($template, \DateTimeInterface $now, \DateTimeInterface $end, $limit = 60, $duration = 1)
    {
        $this->template = $template;
        $this->now      = $now > $end ? $end : $now;
        $this->end      = $end;
        $this->limit    = $limit;
        $this->duration = $duration;
    }

    /**
     * @return \Iterator|Frame[]
     */
    public function getSequence()
    {
        $interval = floatval($this->end->getTimestamp() - $this->now->getTimestamp());
        $duration = floatval($this->duration);
        $limit    = $this->limit;

        while ($interval >= 0 && $limit-- > 0) {
            yield new FrameInstance($this->template, $interval,  $duration);

            $interval -= $duration;
        }
    }
}