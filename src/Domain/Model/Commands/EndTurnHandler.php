<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\EndOfGameResolver;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ReadOnlyPlayers;

final class EndTurnHandler
{
    /**
     * @var ReadOnlyPlayers
     */
    private $players;

    /**
     * @var EndOfGameResolver
     */
    private $resolver;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param ReadOnlyPlayers $players
     * @param EndOfGameResolver $resolver
     * @param Publisher $publisher
     */
    public function __construct(
        ReadOnlyPlayers $players,
        EndOfGameResolver $resolver,
        Publisher $publisher
    ) {
        $this->players = $players;
        $this->resolver = $resolver;
        $this->publisher = $publisher;
    }

    public function onPlayerTurnHasEnded(PlayerTurnHasEnded $event)
    {
        if ($this->players->allPlayersOfGameHavePlayed()) {
            $this->__invoke(new EndTurn());
        }
    }

    /**
     * @param EndTurn $command
     */
    public function __invoke(EndTurn $command)
    {
        // todo when all players are in end turn state
        if ($this->resolver->gameIsEnded()) {
            $this->publisher->publish(new GameHasEnded());
        } else {
            $this->publisher->publish(new TurnWasStarted());
        }
    }
}
