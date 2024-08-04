<?php

declare(strict_types=1);

namespace PhluxorExample;

use Phluxor\ActorSystem\ReenterAfterInterface;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class PongTask implements ReenterAfterInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(mixed $res, ?Throwable $error): void
    {
        if ($error !== null) {
            $this->logger->error("Failed to handle", ['message' => $res]);
            return;
        }

        switch (true) {
            case $res instanceof Message\Pong:
                $this->logger->info("Received pong response", ['response' => $res]);
                break;
            default:
                $this->logger->error("Received unexpected response", ['response' => $res]);
                break;
        }
    }
}
