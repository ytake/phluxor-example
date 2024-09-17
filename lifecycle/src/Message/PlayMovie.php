<?php

declare(strict_types=1);

namespace PhluxorExample\Message;

readonly class PlayMovie
{
    public function __construct(
        public string $movie,
        public int $userId
    ) {
    }
}
