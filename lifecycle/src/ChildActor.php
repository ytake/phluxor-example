<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Message\Restarting;
use PhluxorExample\Message\Recover;

class ChildActor implements ActorInterface
{
    /**
     * @throws \Exception
     */
    public function receive(ContextInterface $context): void
    {
        $message = $context->message();
        switch (true) {
            case $message instanceof Restarting:
                $context->logger()->info("ChildActor restarting");
                break;
            case $message instanceof Recover:
                throw new \Exception('child actor exception');
        }
    }
}
