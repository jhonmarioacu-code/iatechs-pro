<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Log\Logger as IlluminateLogger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Logger as MonologLogger;

class JsonLogTap
{
    public function __invoke(IlluminateLogger|MonologLogger $logger): void
    {
        $baseLogger = $logger instanceof IlluminateLogger
            ? $logger->getLogger()
            : $logger;

        if (!$baseLogger instanceof MonologLogger) {
            return;
        }

        foreach ($baseLogger->getHandlers() as $handler) {
            if ($handler instanceof FormattableHandlerInterface) {
                $handler->setFormatter(new JsonFormatter());
            }
        }
    }
}
