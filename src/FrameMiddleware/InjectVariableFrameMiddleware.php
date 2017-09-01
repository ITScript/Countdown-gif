<?php

namespace ITS\Countdown\GIF\FrameMiddleware;

use ITS\Countdown\GIF\Frame;
use ITS\Countdown\GIF\Frame\FrameInstance;
use ITS\Countdown\GIF\FrameMiddleware;

class InjectVariableFrameMiddleware implements FrameMiddleware
{
    /**
     * @var callable
     */
    protected $substitutionGenerator;

    /**
     * SvgToGifFrameMiddleware constructor.
     * @param callable|null $substitutionGenerator
     */
    public function __construct(callable $substitutionGenerator = null)
    {
        $this->substitutionGenerator = $substitutionGenerator ?: [$this, 'convertMicroTime'];
    }

    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null)
    {
        $content = strtr($frame->getContent(), call_user_func($this->substitutionGenerator, $frame->getInterval()));

        $frame = new FrameInstance($content, $frame->getInterval(), $frame->getDuration());

        if (is_callable($next)) {
            return $next($frame);
        }

        return $frame;
    }

    /**
     * @param float $time
     * @return array
     */
    protected function convertMicroTime($time)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime('@' . intval($time));

        $ms = intval(fmod($time, 1) * 1000);

        list ($d, $h, $m, $s) = explode(',', $dtF->diff($dtT)->format('%a,%h,%i,%s'));

        return [
            '{d1}' => intval($d / 100),
            '{d2}' => intval(($d % 100) / 10),
            '{d3}' => $d % 10,
            '{h1}' => intval($h / 10),
            '{h2}' => $h % 10,
            '{m1}' => intval($m / 10),
            '{m2}' => $m % 10,
            '{s1}' => intval($s / 10),
            '{s2}' => $s % 10,
            '{ms1}' => intval($ms / 100),
            '{ms2}' => intval(($ms % 100) / 10),
            '{ms3}' => $ms % 10,
        ];
    }
}