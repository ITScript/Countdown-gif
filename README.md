# Countdown-gif
Flexible countdown builder, supports svg templating

# Installation
You can install directly via Composer:
```bash
$ composer require "itscript/countdown-gif":"^1.0"
```

## Simple usage
```php
<?php

require 'vendor/autoload.php'

$template = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="100" viewBox="0 0 400 100">
    <defs>
        <style>
            .cls-1 {
                font-size: 62.5px;
                text-anchor: middle;
                font-weight: 900;
            }
        </style>
    </defs>
    <text id="h1" class="cls-1" x="25" y="72"><tspan x="25">{h1}</tspan></text>
    <text id="h2" class="cls-1" x="75" y="72"><tspan x="75">{h2}</tspan></text>
    <text id=":1" class="cls-1" x="124" y="66"><tspan x="124">:</tspan></text>
    <text id="m1" class="cls-1" x="175" y="72"><tspan x="175">{m1}</tspan></text>
    <text id="m2" class="cls-1" x="225" y="71"><tspan x="225">{m2}</tspan></text>
    <text id=":2" class="cls-1" x="275" y="66"><tspan x="275">:</tspan></text>
    <text id="s1" class="cls-1" x="325" y="72"><tspan x="325">{s1}</tspan></text>
    <text id="s2" class="cls-1" x="375" y="71"><tspan x="375">{s2}</tspan></text>
</svg>
XML;

$now = new \DateTimeImmutable();
$end = $now->add(new \DateInterval('PT45296S'));

$sequence = new \ITS\Countdown\GIF\FrameSequenceBuilder\DateTimeFrameSequenceBuilder($template, $now, $end);

$middleware = new \ITS\Countdown\GIF\FrameMiddleware\CompositeFrameMiddleware(
    new \ITS\Countdown\GIF\FrameMiddleware\InjectVariableFrameMiddleware(),
    new \ITS\Countdown\GIF\FrameMiddleware\SvgToGifFrameMiddleware()
);

$sequence = new \ITS\Countdown\GIF\FrameSequenceBuilder\MiddlewareFrameSequenceBuilder($sequence->getSequence(), $middleware);
$handler = new \ITS\Countdown\GIF\FrameSequenceHandler\SaveFrameSequenceHandler();

header("Content-Type: image/gif");
$handler->process($sequence->getSequence());
```

## Caching
For optimization you can use cache for each frame.
Just add caching middleware, as below
```php
<?php

$middleware = new \ITS\Countdown\GIF\FrameMiddleware\CompositeFrameMiddleware(/* ... */);

/** @var \Psr\SimpleCache\CacheInterface $cache */
$cache;

$middleware
    ->add(
        new \ITS\Countdown\GIF\FrameMiddleware\CallbackFrameMiddleware(function (\ITS\Countdown\GIF\Frame $frame, $next) use ($cache) {
            $content = $cache->get(number_format($frame->getInterval()));
            if ($content) { // exit if cache exists
                return new \ITS\Countdown\GIF\Frame\FrameInstance($content, $frame->getInterval(), $frame->getDuration());
            } elseif (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        }),
        2 // beginning
    )
    ->add(
        new \ITS\Countdown\GIF\FrameMiddleware\CallbackFrameMiddleware(function (\ITS\Countdown\GIF\Frame $frame, $next) use ($cache) {
            $cache->set(number_format($frame->getInterval()), $frame->getContent(), 60);

            if (is_callable($next)) {
                return $next($frame);
            } else {
                return $frame;
            }
        }),
        0 // end
    );
```

## Interfaces
```php 
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

interface FrameMiddleware
{
    /**
     * @param Frame         $frame
     * @param callable|null $next
     * @return Frame
     */
    public function __invoke(Frame $frame, callable $next = null);
}

interface FrameSequenceBuilder
{
    /**
     * @return \Iterator|Frame[]
     */
    public function getSequence();
}

interface FrameSequenceHandler
{
    /**
     * @param \Iterator|Frame[] $sequence
     */
    public function process(\Iterator $sequence);
}
```
