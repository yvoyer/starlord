<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

final class PlayerJoinedGame implements StarLordEvent
{
    /**
     * @var int
     */
    private $playerId;

    /**
     * @param int $playerId
     */
    public function __construct(int $playerId)
    {
        $this->playerId = $playerId;
    }

    /**
     * @return int
     */
    public function playerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'name' => 'player_joined_game',
            'player' => $this->playerId,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
