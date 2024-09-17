<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;

class ChildActor implements ActorInterface
{
    public function receive(ContextInterface $context): void
    {
        // TODO: Implement receive() method.
    }
}
