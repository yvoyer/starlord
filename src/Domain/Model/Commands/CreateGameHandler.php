<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\GameWasCreated;
use StarLord\Domain\Model\Publisher;

final class CreateGameHandler
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param Publisher $publisher
     */
    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param CreateGame $command
     */
    public function __invoke(CreateGame $command)
    {
        $this->publisher->publish(new GameWasCreated($command->players()));
    }
}
