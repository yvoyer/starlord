<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Persistence\InMemory;

use StarLord\Domain\Model\WriteOnlyPlayer;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class PlayerCollection implements WriteOnlyPlayers, \Countable
{
    /**
     * @var WriteOnlyPlayer[]
     */
    private $players = [];

    /**
     * @param WriteOnlyPlayer[] $players
     */
    public function __construct(array $players = [])
    {
        array_map(
            function (int $id) use ($players) {
                $this->savePlayer($id, $players[$id]);
            },
            array_keys($players)
        );
    }

    /**
     * @param int $id
     * @param WriteOnlyPlayer $player
     */
    public function savePlayer(int $id, WriteOnlyPlayer $player)
    {
        $this->players[$id] = $player;
    }

    /**
     * @param int $id
     *
     * @return WriteOnlyPlayer
     */
    public function getPlayerWithId(int $id): WriteOnlyPlayer
    {
        if (! $this->playerExists($id)) {
            throw new \RuntimeException("Player with id '{$id}' do not exists.");
        }

        return $this->players[$id];
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function playerExists(int $id): bool
    {
        return array_key_exists($id, $this->players);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->players);
    }

    /**
     * todo inject game id
     * @return WriteOnlyPlayer[]
     */
    public function playersOfGame(): array
    {
        return $this->players;
    }
}
