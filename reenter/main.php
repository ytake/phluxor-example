<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Phluxor\ActorSystem;
use Phluxor\ActorSystem\Props;
use Phluxor\Router\RoundRobin\PoolRouter;
use PhluxorExample\Message\Ping;
use PhluxorExample\Message\Pong;
use PhluxorExample\Message\Tick;
use PhluxorExample\PingActor;
use Swoole\Coroutine;

use Swoole\Timer;

use function Swoole\Coroutine\run;

function main(): void
{
    run(function () {
        \Swoole\Coroutine\go(function () {
            $system = ActorSystem::create();
            $props = PoolRouter::create(
                10,
                Props::withFunc(
                    new ActorSystem\Message\ReceiveFunction(
                        function (ActorSystem\Context\ContextInterface $context): void {
                            $message = $context->message();
                            if ($message instanceof Ping) {
                                $remainder = $message->count % 3;
                                if ($remainder === 0) {
                                    $sleep = 1700;
                                } elseif ($remainder === 1) {
                                    $sleep = 300;
                                } else {
                                    $sleep = 2900;
                                }
                                Coroutine::sleep($sleep / 1000);
                                $context->logger()->info("received ping", ['ref' => (string) $context->self()]);
                                $context->respond(new Pong($message->count));
                            }
                        }
                    )
                )
            );
            $pongRouter = $system->root()->spawn($props);
            $ping = $system->root()->spawn(
                Props::fromProducer(fn() => new PingActor($pongRouter))
            );
            $finish = new Coroutine\Channel(1);
            Coroutine\go(function () use ($finish) {
                Coroutine\System::waitSignal(SIGINT, SIGTERM);
                $finish->push(true);
            });
            $count = 0;
            $timer = Timer::tick(1000, function () use (&$count, $ping, $system) {
                $count++;
                $system->root()->send($ping, new Tick($count));
            });
            Coroutine\go(function () use ($finish, $timer) {
                $finish->pop();
                Timer::clear($timer);
            });
        });
    });
}

main();
