<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Ref;
use PhluxorExample\Message\FizzBuzz;
use PhluxorExample\Message\Say;

readonly class SlipRouter implements ActorInterface
{
    use RouteSlipTrait;

    public function __construct(
        private Ref $fizz,
        private Ref $buzz,
        private Ref $endStep
    ) {
    }

    public function createRouteSlip(): array
    {
        $routeSlip = [];
        $routeSlip[] = $this->fizz;
        $routeSlip[] = $this->buzz;
        return array_merge($routeSlip, [$this->endStep]);
    }

    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        if ($msg instanceof Say) {
            $this->sendMessageToNextTask($context, $this->createRouteSlip(), new FizzBuzz($msg->count));
        }
    }
}
