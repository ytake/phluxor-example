<?php

declare(strict_types=1);

namespace PhluxorExample\Message;

readonly class Pong
{
    public function __construct(
        public int $count
    ) {}
}
