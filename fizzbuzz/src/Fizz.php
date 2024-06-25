<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use PhluxorExample\Message\FizzBuzz;
use PhluxorExample\Message\RouteSlip;

class Fizz implements ActorInterface
{
    use RouteSlipTrait;

    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        if ($msg instanceof RouteSlip) {
            $fizzbuzz = new FizzBuzz($msg->fizzBuzz->number);
            if ($msg->fizzBuzz->number % 3 == 0) {
                $fizzbuzz = new FizzBuzz($msg->fizzBuzz->number, 'Fizz');
            }
            $this->sendMessageToNextTask($context, $msg->routeSlip, $fizzbuzz);
        }
    }
}
