<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Ref;
use PhluxorExample\Message\Ping;

readonly class PingActor implements ActorInterface
{
    public function __construct(
        private Ref $routerRef
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function receive(ContextInterface $context): void
    {
        $message = $context->message();
        switch (true) {
            case $message instanceof Message\Tick:
                $ping = new Ping($message->count);
                $future = $context->requestFuture($this->routerRef, $ping, 2500);
                $count = $message->count;
                $context->reenterAfter($future, new PongTask($context->logger()));
                break;
        }
    }
}
