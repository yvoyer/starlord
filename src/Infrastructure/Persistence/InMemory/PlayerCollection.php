<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Persistence\InMemory;

use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ReadOnlyPlayer;
use StarLord\Domain\Model\ReadOnlyPlayers;
use StarLord\Domain\Model\WriteOnlyPlayer;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class PlayerCollection implements WriteOnlyPlayers, ReadOnlyPlayers, \Countable
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
            function (WriteOnlyPlayer $player) {
                $this->savePlayer($player->getIdentity(), $player);
            },
            $players
        );
    }

    /**
     * @param PlayerId $id
     * @param WriteOnlyPlayer $player
     */
    public function savePlayer(PlayerId $id, WriteOnlyPlayer $player)
    {
        $this->players[$id->toString()] = $player;
    }

    /**
     * @param PlayerId $id
     *
     * @return WriteOnlyPlayer
     */
    public function getPlayerWithId(PlayerId $id): WriteOnlyPlayer
    {
        if (! $this->playerExists($id)) {
            throw new \RuntimeException("Player with id '{$id}' do not exists.");
        }

        return $this->players[$id->toString()];
    }

    /**
     * @param PlayerId $id
     *
     * @return bool
     */
    public function playerExists(PlayerId $id): bool
    {
        return array_key_exists($id->toString(), $this->players);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->players);
    }

    /**
     * @return WriteOnlyPlayer[]
     */
    public function playersOfGame(): array
    {
        return array_values($this->players);
    }

    /**
     * @return bool
     */
    public function allPlayersOfGameHavePlayed(): bool
    {
        return count(array_filter(
            $this->players,
            function (ReadOnlyPlayer $player) {
                return ! $player->hasPlayed();
            }
        )) === 0;
    }

    /**
     * @param PlayerId $id
     *
     * @return Colons
     * @throws EntityNotFoundException
     */
    public function availableColons(PlayerId $id): Colons
    {
//        if (! isset($this->players[$id->toString()])) {
            throw EntityNotFoundException::objectWithAttribute(
                ReadOnlyPlayer::class,
                'identity',
                $id->toString()
            );
  //      }

    //    return $this->players[$id->toString()];
    }
}
