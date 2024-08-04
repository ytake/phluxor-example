<?php

declare(strict_types=1);

namespace PhluxorExample\Message;

readonly class Ping
{
    public function __construct(
        public int $count
    ) {}
}
