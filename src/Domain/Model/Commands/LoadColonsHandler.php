<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\Exception\MissingColonsException;
use StarLord\Domain\Model\PlayerArmada;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ReadOnlyPlayers;

final class LoadColonsHandler
{
    /**
     * @var ReadOnlyPlayers
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
     * @param ReadOnlyPlayers $players
     * @param PlayerArmada $armada
     * @param Publisher $publisher
     */
    public function __construct(
        ReadOnlyPlayers $players,
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
//        $player = $this->players->playerWithId($command->playerId());
        $availableColons = $this->players->availableColons($command->playerId());
        if ($availableColons->lowerThan($command->quantity())) {
            throw new MissingColonsException(
                sprintf(
                    'Player with id "%s" do not have all required colons. Requires at least "%s", got "%s".',
                    $command->playerId()->toString(),
                    $command->quantity(),
                    $availableColons->toInt()
                )
            );
        }

        // todo reomve coloon from player
        $ship = $this->armada->shipWithId($command->shipId(), $command->playerId());
        $ship->loadColons(new Colons($command->quantity()));

        $this->armada->saveShip($ship);
    }
}
