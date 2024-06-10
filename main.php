<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

function main(): void
{
    \Swoole\Coroutine\run(function () {
        \Swoole\Coroutine\go(function () {
            $system = \Phluxor\ActorSystem::create();
            $pipe = $system->root()->spawn(
                \Phluxor\ActorSystem\Props::fromFunction(
                    new \Phluxor\ActorSystem\Message\ReceiveFunction(
                        function (\Phluxor\ActorSystem\Context\ContextInterface $context): void {
                            $msg = $context->message();
                            if ($msg instanceof \PhluxorExample\Event\ClassFinished) {
                                $context->logger()->info(
                                    sprintf('クラスが終了しました: %s', $msg->getSubject())
                                );
                            }
                        }
                    )
                )
            );
            $stream = $system->root()->spawnNamed(
                \Phluxor\ActorSystem\Props::fromProducer(
                    fn()  => new \PhluxorExample\Classroom\Actor($pipe, range(1, 20))
                ),
                'math-classroom'
            );
            $system->root()->send($stream->getPid(), new \PhluxorExample\Command\StartsClass(['subject' => 'math']));
        });
    });
}

main();