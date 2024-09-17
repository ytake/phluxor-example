<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Phluxor\ActorSystem;

use PhluxorExample\Message\PlayMovie;
use PhluxorExample\Message\Recover;
use PhluxorExample\PlaybackActor;

use function Swoole\Coroutine\run;

function main(): void
{
    run(function () {
        \Swoole\Coroutine\go(function () {
            $system = ActorSystem::create();
            $ref = $system->root()->spawn(
                ActorSystem\Props::fromProducer(
                    fn() => new PlaybackActor()
                )
            );
            $system->root()->send($ref, new PlayMovie('Transformers', 1));
            $system->root()->send($ref, new PlayMovie('Transformers last knight', 2));
            $system->root()->send($ref, new PlayMovie('Transformers age of extinction', 3));
            $system->root()->send($ref, new PlayMovie('Transformers dark of the moon', 4));
            $system->root()->send($ref, new PlayMovie('Transformers revenge of the fallen', 5));
            $system->getLogger()->info('restarting actor');
            $system->root()->send($ref, new Recover());
            $system->root()->send($ref, new PlayMovie('Transformers One', 6));
            $system->root()->send($ref, new PlayMovie('Transformers The Movie', 7));
            $system->getLogger()->info('stopping actor');
            $system->root()->poison($ref);
        });
    });
}

main();
