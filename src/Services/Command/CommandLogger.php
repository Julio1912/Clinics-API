<?php

namespace App\Services\Command;

use App\Services\Command\CommandConstants;

trait CommandLogger
{
    /**
     * Logger callback for logging messages when working in command
     *
     * @var callable
     */
    protected $logger;

    /**
     * Sets the logger callable to execute with the log() method.
     *
     * @param callable $logger
     */
    public function setLogger(callable $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs a message using the logger.
     *
     * @param string $message
     */
    public function log(string $message, int $messageType = CommandConstants::MSG_SUCCESS)
    {
        $logger = $this->logger;

        $logger($message, $messageType);
    }
}