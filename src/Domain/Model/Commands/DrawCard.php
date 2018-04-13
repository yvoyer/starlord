<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

final class DrawCard
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
}
