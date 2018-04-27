<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use Assert\Assertion;
use StarLord\Domain\Model\PlayerId;

final class GameWasStarted implements StarLordEvent
{
    /**
     * @var PlayerId[]
     */
    private $players;

    /**
     * @param PlayerId[] $players
     */
    public function __construct(array $players)
    {
        Assertion::allIsInstanceOf($players, PlayerId::class);
        $this->players = $players;
    }

    /**
     * @return PlayerId[]
     */
    public function players(): array
    {
        return $this->players;
    }

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
