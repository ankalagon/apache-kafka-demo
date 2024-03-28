<?php

namespace Demo;

use Demo\Infrastructure\Queue\Engine;

class Consumer
{
    public function __construct(private Engine $engine) {
    }

    public function consume()
    {
        return $this->engine->consume();
    }
}
