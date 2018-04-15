<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

final class GameWasStarted implements StarLordEvent
{
    /**
     * @return string
     */
    public function serialize()
    {
        return json_encode(['name' => 'game_was_started']);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
