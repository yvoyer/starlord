<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

final class TurnWasStarted implements StarLordEvent
{
    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(['name' => 'turn_was_started']);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
