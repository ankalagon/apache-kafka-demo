<?php

namespace Demo\Infrastructure\Queue;

interface Engine
{
    public function publish($payload): bool;

    public function consume();
}
