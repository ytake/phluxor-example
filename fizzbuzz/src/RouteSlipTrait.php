<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use PhluxorExample\Message\FizzBuzz;

trait RouteSlipTrait
{
    /**
     * enterprise integration pattern / messaging pattern routing slip implementation
     *
     * @param ContextInterface $context
     * @param array $routeSlip
     * @param FizzBuzz $message
     * @return void
     */
    public function sendMessageToNextTask(ContextInterface $context, array $routeSlip, FizzBuzz $message): void
    {
        $nextTask = $routeSlip[0];
        $newSlip = array_slice($routeSlip, 1);
        if (count($newSlip) == 0) {
            $context->send($nextTask, $message);
            return;
        }
        $context->send(
            $nextTask,
            new \PhluxorExample\Message\RouteSlip(
                $newSlip, $message,
            )
        );
    }
}
