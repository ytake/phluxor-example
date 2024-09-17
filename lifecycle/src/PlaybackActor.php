<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Message\Restarting;
use Phluxor\ActorSystem\Message\Started;
use Phluxor\ActorSystem\Message\Stopping;
use Phluxor\ActorSystem\Props;
use PhluxorExample\Message\PlayMovie;
use PhluxorExample\Message\Recover;

class PlaybackActor implements ActorInterface
{
    public function receive(ContextInterface $context): void
    {
        $message = $context->message();
        switch (true) {
            case $message instanceof Started:
                $context->logger()->info("PlaybackActor started");
                break;
            case $message instanceof Restarting:
                $context->logger()->info("ChildActor restarting");
                break;
            case $message instanceof Recover:
                $this->recoverMessageHandler($context);
                break;
            case $message instanceof Stopping:
                $context->logger()->info("PlaybackActor stopping");
                break;
            case $message instanceof PlayMovie:
                $context->logger()->info("Playing movie $message->movie for user $message->userId");
                break;
        }
    }

    private function recoverMessageHandler(ContextInterface $context): void
    {
        if (count($context->children()) === 0) {
            $child = $context->spawn(Props::fromProducer(fn() => new ChildActor()));
        } else {
            $child = $context->children()[0];
        }
        $context->forward($child);
    }
}
