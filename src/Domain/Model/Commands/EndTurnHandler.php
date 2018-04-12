<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\EndOfGameResolver;
use StarLord\Domain\Model\Publisher;

final class EndTurnHandler
{
    /**
     * @var EndOfGameResolver
     */
    private $resolver;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param EndOfGameResolver $resolver
     * @param Publisher $publisher
     */
    public function __construct(EndOfGameResolver $resolver, Publisher $publisher)
    {
        $this->resolver = $resolver;
        $this->publisher = $publisher;
    }

    /**
     * @param EndTurn $command
     */
    public function __invoke(EndTurn $command)
    {
        if ($this->resolver->gameIsEnded()) {
            $this->publisher->publish(new GameHasEnded());
        } else {
            $this->publisher->publish(new TurnWasStarted());
        }
    }
}
