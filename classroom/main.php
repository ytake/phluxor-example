<?php

declare(strict_types=1);

use Phluxor\ActorSystem;
use Phluxor\ActorSystem\Context\ContextInterface;
use Phluxor\ActorSystem\Message\ReceiveFunction;
use Phluxor\ActorSystem\Props;
use PhluxorExample\Classroom\Actor;
use PhluxorExample\Command\StartsClass;
use PhluxorExample\Event\ClassFinished;

use function Swoole\Coroutine\run;

require_once 'vendor/autoload.php';

run(function () {
    \Swoole\Coroutine\go(function () {
        $system = ActorSystem::create();
        $pipe = $system->root()->spawn(
            Props::fromFunction(
                new ReceiveFunction(
                    function (ContextInterface $context): void {
                        $msg = $context->message();
                        if ($msg instanceof ClassFinished) {
                            $context->logger()->info(
                                sprintf('クラスが終了しました: %s', $msg->getSubject())
                            );
                        }
                    }
                )
            )
        );
        $stream = $system->root()->spawnNamed(
            Props::fromProducer(
                fn() => new Actor($pipe, range(1, 20))
            ),
            'math-classroom'
        );
        $system->root()->send($stream->getRef(), new StartsClass(['subject' => 'math']));
    });
});
