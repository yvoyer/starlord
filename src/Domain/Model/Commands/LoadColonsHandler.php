<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\ColonsWereLoaded;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\Exception\MissingColonsException;
use StarLord\Domain\Model\PlayerArmada;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class LoadColonsHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var PlayerArmada
     */
    private $armada;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param PlayerArmada $armada
     * @param Publisher $publisher
     */
    public function __construct(
        WriteOnlyPlayers $players,
        PlayerArmada $armada,
        Publisher $publisher
    ) {
        $this->players = $players;
        $this->armada = $armada;
        $this->publisher = $publisher;
    }

    /**
     * @param LoadColons $command
     */
    public function __invoke(LoadColons $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $availableColons = $player->availableColons();
        if ($availableColons->lowerThan($command->quantity())) {
            throw new MissingColonsException(
                sprintf(
                    'Player with id "%s" do not have the required colons. Requires at least "%s", got "%s".',
                    $command->playerId()->toString(),
                    $command->quantity(),
                    $availableColons->toInt()
                )
            );
        }

        $ship = $this->armada->shipWithId($command->shipId(), $command->playerId());

        $quantity = new Colons($command->quantity());
        $player->loadColons($quantity);
        $ship->loadColons($quantity);

        $this->players->savePlayer($command->playerId(), $player);
        $this->armada->saveShip($ship);
        $this->publisher->publish(
            new ColonsWereLoaded(
                $command->playerId(),
                $quantity,
                $command->shipId()
            )
        );
    }
}
