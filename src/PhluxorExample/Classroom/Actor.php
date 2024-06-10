<?php

declare(strict_types=1);

namespace PhluxorExample\Classroom;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Pid;
use Phluxor\ActorSystem\Props;
use PhluxorExample\Command\FinishTest;
use PhluxorExample\Command\PrepareTest;
use PhluxorExample\Command\StartsClass;
use PhluxorExample\Event\ClassFinished;
use PhluxorExample\Teacher\Actor as TeacherActor;

readonly class Actor implements ActorInterface
{
    /**
     * @param Pid $stream
     * @param int[] $students
     */
    public function __construct(
        private Pid $stream,
        private array $students
    ) {
    }

    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        switch (true) {
            case $msg instanceof StartsClass:
                $ref = $context->spawn(
                    Props::fromProducer(
                        fn() => new TeacherActor(
                            $this->students, $context->self()
                        )
                    )
                );
                $context->send($ref, new PrepareTest(['subject' => $msg->getSubject()]));
                break;
            case $msg instanceof FinishTest:
                $context->send($this->stream, new ClassFinished([
                    'subject' => $msg->getSubject(),
                ]));
                $context->poison($context->self());
                break;
        }
    }
}
