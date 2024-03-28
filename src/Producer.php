<?php

namespace Demo;

use Demo\Infrastructure\Queue\Engine;

class Producer
{
    public function __construct(private Engine $engine) {
    }

    public function publish($payload)
    {
        $this->engine->publish($payload);
    }
}
