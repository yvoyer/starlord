<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\GameContext;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class EndTurnHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var GameContext
     */
    private $resolver;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param GameContext $resolver
     * @param Publisher $publisher
     */
    public function __construct(
        WriteOnlyPlayers $players,
        GameContext $resolver,
        Publisher $publisher
    ) {
        $this->players = $players;
        $this->resolver = $resolver;
        $this->publisher = $publisher;
    }

    /**
     * @param PlayerTurnHasEnded $event
     */
    public function onPlayerTurnHasEnded(PlayerTurnHasEnded $event)
    {
        $playersWhoEndedTheirTurn = [];
        $players = $this->players->playersOfGame();
        foreach ($players as $player) {
            if ($player->turnIsDone()) {
                $playersWhoEndedTheirTurn[] = $player;
            }
        }

        $allPlayersPlayed = count($playersWhoEndedTheirTurn) === count($players);
        if (! $allPlayersPlayed) {
            return;
        }

        if ($this->resolver->gameIsEnded()) {
            $this->publisher->publish(new GameHasEnded());
        } else {
            $this->publisher->publish(new TurnWasStarted());
        }
    }
}
