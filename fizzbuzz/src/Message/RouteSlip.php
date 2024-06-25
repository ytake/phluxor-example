<?php

declare(strict_types=1);

namespace PhluxorExample\Message;

use Phluxor\ActorSystem\Ref;

readonly class RouteSlip
{
    /**
     * @param Ref[] $routeSlip
     * @param FizzBuzz $fizzBuzz
     */
    public function __construct(
        public array $routeSlip,
        public FizzBuzz $fizzBuzz
    ) {
    }
}
