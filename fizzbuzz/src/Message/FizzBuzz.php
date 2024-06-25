<?php

declare(strict_types=1);

namespace PhluxorExample\Message;

use function sprintf;

readonly class FizzBuzz
{
    /**
     * @param int $number
     * @param string $text
     */
    public function __construct(
        public int $number,
        public string $text = ''
    ) {
    }

    public function __toString(): string
    {
        return sprintf('%d: %s', $this->number, $this->text);
    }
}
