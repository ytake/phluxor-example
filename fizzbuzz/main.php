<?php

declare(strict_types=1);

use Phluxor\ActorSystem;
use Phluxor\ActorSystem\Props;
use PhluxorExample\Fizz;
use PhluxorExample\Buzz;
use PhluxorExample\Message\FizzBuzz;
use PhluxorExample\Message\Say;

use function Swoole\Coroutine\run;

require_once 'vendor/autoload.php';

function main(): void
{
    run(function () {
        \Swoole\Coroutine\go(function () {
            $range = 100;
            $system = ActorSystem::create();
            $c = new ActorSystem\Channel\TypedChannel(
                $system,
                fn(mixed $msg): bool => $msg instanceof FizzBuzz
            );
            \Swoole\Coroutine\go(function () use ($system, $c, $range) {
                $router = $system->root()->spawn(
                    Props::fromProducer(function () use ($system, $c) {
                        return new \PhluxorExample\SlipRouter(
                            fizz: $system->root()->spawn(Props::fromProducer(fn() => new Fizz())),
                            buzz: $system->root()->spawn(Props::fromProducer(fn() => new Buzz())),
                            endStep: $c->getRef()
                        );
                    })
                );
                foreach (range(1, $range) as $v) {
                    $system->root()->send($router, new Say($v));
                }
            });
            foreach (range(1, $range) as $v) {
                echo $c->result() . "\r\n";
            }
        });
    });
}

main();
