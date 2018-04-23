<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use Assert\Assertion;
use StarLord\Domain\Model\PlayerId;

final class CreateGame
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
}
