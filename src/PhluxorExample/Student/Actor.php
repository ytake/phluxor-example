<?php

declare(strict_types=1);

namespace PhluxorExample\Student;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use PhluxorExample\Command\StartTest;
use PhluxorExample\Command\SubmitTest;
use Random\RandomException;

class Actor implements ActorInterface
{
    /**
     * @throws RandomException
     */
    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        if ($msg instanceof StartTest) {
            sleep(random_int(1, 9));
            $context->logger()->info(
                sprintf(
                    '%s が %s テストの解答を提出します',
                    $context->self()?->protobufPid()->getId(),
                    $msg->getSubject()
                )
            );
            $context->send($context->parent(), new SubmitTest([
                'subject' => $msg->getSubject(),
                'name' => $context->self()?->protobufPid()->getId(),
            ]));
            $context->poison($context->self());
        }
    }
}
