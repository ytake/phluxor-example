<?php

declare(strict_types=1);

namespace PhluxorExample\Teacher;

use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ActorInterface;
use Phluxor\ActorSystem\Message\Restarting;
use Phluxor\ActorSystem\Pid;
use Phluxor\ActorSystem\Props;
use PhluxorExample\Command\FinishTest;
use PhluxorExample\Command\PrepareTest;
use PhluxorExample\Command\StartTest;
use PhluxorExample\Command\SubmitTest;
use PhluxorExample\Student\Actor as StudentActor;

class Actor implements ActorInterface
{
    /** @var SubmitTest[] */
    private array $endOfTests = [];

    /**
     * @param int[] $students
     * @param Pid $replyTo
     */
    public function __construct(
        private readonly array $students,
        private readonly Pid $replyTo
    ) {
    }

    public function receive(ContextInterface $context): void
    {
        $msg = $context->message();
        switch (true) {
            case $msg instanceof Restarting:
                $context->send($context->self(), new PrepareTest(['subject' => 'math']));
                break;
            case $msg instanceof PrepareTest:
                $context->logger()->info(sprintf('先生が%sテストを出しました', $msg->getSubject()));
                foreach ($this->students as $student) {
                    $ref = $context->spawnNamed(
                        Props::fromProducer(fn() => new StudentActor()),
                        sprintf('student-%d', $student)
                    );
                    if ($ref->isError()) {
                        // $context->logger()->error(sprintf('生徒 %d 生成できませんでした', $student));
                        throw new \RuntimeException('生徒生成失敗');
                    }
                    $context->send($ref->getPid(), new StartTest(['subject' => $msg->getSubject()]));
                }
                $this->endOfTests[] = $context->self();
                break;
            case $msg instanceof SubmitTest:
                $this->endOfTests[] = $msg;
                if (count($this->endOfTests) === count($this->students)) {
                    $context->send($this->replyTo, new FinishTest(['subject' => 'math']));
                    $context->poison($context->self());
                }
                break;
        }
    }
}
