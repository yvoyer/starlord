<?php declare(strict_types=1);

namespace StarLord\Application;

use StarLord\Domain\Events\StarLordEvent;
use StarLord\Domain\Model\Bonus\CollectResourcesFromCrystals;
use StarLord\Domain\Model\Bonus\CollectResourcesFromPlanets;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Cards\CardRegistry;
use StarLord\Domain\Model\Cards\DeckBuilder;
use StarLord\Domain\Model\ColoredPlanet;
use StarLord\Domain\Model\Commands\ColonizePlanetHandler;
use StarLord\Domain\Model\Commands\CreateGameHandler;
use StarLord\Domain\Model\Commands\DrawCardHandler;
use StarLord\Domain\Model\Commands\EndPlayerTurnHandler;
use StarLord\Domain\Model\Commands\EndTurnHandler;
use StarLord\Domain\Model\Commands\LoadColonsHandler;
use StarLord\Domain\Model\Commands\MinePlanetHandler;
use StarLord\Domain\Model\Commands\MoveShipHandler;
use StarLord\Domain\Model\Commands\PlayCardHandler;
use StarLord\Domain\Model\Commands\SelectHomeWorldHandler;
use StarLord\Domain\Model\Commands\StartPlayerTurnHandler;
use StarLord\Domain\Model\Commands\UnloadColonsHandler;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\GameSettings;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ReadOnlyPlanet;
use StarLord\Domain\Model\ReadOnlyPlayer;
use StarLord\Domain\Model\Setup\PlayerSetup;
use StarLord\Domain\Model\Setup\StartingCredit;
use StarLord\Domain\Model\Setup\StartingDeuterium;
use StarLord\Domain\Model\Setup\StartingSpaceships;
use StarLord\Domain\Model\ShipId;
use StarLord\Domain\Model\TestShip;
use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WriteOnlyPlayers;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;
use StarLord\Infrastructure\Persistence\InMemory\ShipCollection;

final class StarLordGame
{
    /**
     * @var \StarLord\Domain\Model\Publisher
     */
    private $publisher;

    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var CardRegistry
     */
    private $cardRegistry;

    /**
     * @var World
     */
    private $world;

    public function __construct(WriteOnlyPlayers $players)
    {
        $this->players = $players;
        $publisher = new class() implements Publisher {
            private $subscribers = [];
            private $logger;

            public function __construct()
            {
                $this->disableDebug();
            }

            public function enableDebug()
            {
                $this->logger = new class () {
                    public function log(string $message) {
                        echo($message);
                    }
                };
            }

            public function disableDebug()
            {
                $this->logger = new class () {
                    public function log(string $message) {
                    }
                };
            }

            public function addSubscriber($subscriber) {
                $class = get_class($subscriber);
                $command = str_replace('Handler', '', $class);
                $this->subscribers[$command] = $subscriber;
            }

            public function publish(StarLordEvent $event)
            {
                $class = get_class($event);
                $method = 'on' . ucfirst(substr($class, strrpos($class, '\\') + 1));

                foreach ($this->subscribers as $subscriber) {
                    $subscriberClass = substr(get_class($subscriber), strrpos(get_class($subscriber), '\\') + 1);
                    if (method_exists($subscriber, $method)) {
                        $data = $event->serialize();
                        $this->logger->log("Publish event: '{$data}' to '{$subscriberClass}'.\n");
                        $subscriber->{$method}($event);
                    } else {
                        $this->logger->log("Skipped: {$subscriberClass}. \n");
                    }
                }
            }

            public function handle($command)
            {
                $class = get_class($command);
                $this->subscribers[$class]($command);
            }
        };

        // Cards
        $builder = new DeckBuilder();
        $builder->addPendingCard(1039);
        $builder->addPendingCard(1038);
        $builder->addPendingCard(1037);
        $builder->addPendingCard(1036);
        $builder->addPendingCard(1035);
        $builder->addPendingCard(1034);
        $builder->addPendingCard(1033);
        $builder->addPendingCard(1032);
        $builder->addPendingCard(1031);
        $builder->addPendingCard(1030);
        $builder->addPendingCard(1029);
        $builder->addPendingCard(1028);
        $builder->addPendingCard(1027);
        $builder->addPendingCard(1026);
        $builder->addPendingCard(1025);
        $builder->addPendingCard(1024);
        $builder->addPendingCard(1023);
        $builder->addPendingCard(1022);
        $builder->addPendingCard(1021);
        $builder->addPendingCard(1020);
        $builder->addPendingCard(1019);
        $builder->addPendingCard(1018);
        $builder->addPendingCard(1017);
        $builder->addPendingCard(1016);
        $builder->addPendingCard(1015);

        // Cards in player 3 hands
        $builder->addPendingCard(1004);
        $builder->addPendingCard(1003);
        $builder->addPendingCard(1002);
        $builder->minePlanet(1001, 1); // turn 2
        $builder->minePlanet(1000, 1); // turn 1

        // Cards in player 2 hands
        $builder->addPendingCard(1009);
        $builder->addPendingCard(1008);
        $builder->addPendingCard(1007);
        $builder->minePlanet(1006, 1); // turn 2
        $builder->buildColonists(1005); // turn 1

        // Cards in player 1 hands
        $builder->addPendingCard(1014);
        $builder->addPendingCard(1013);
        $builder->colonizePlanet(1012); // turn 3
        $builder->minePlanet(1011, 1); // turn 2
        $builder->buyTransport(1010, 2); // turn 1

        // Other
        $this->world = new Galaxy([]);
        $this->world->savePlanet($homeWorld_playerOne_blue = new PlanetId(500), ColoredPlanet::blue());
        $this->world->savePlanet($homeWorld_playerTwo_green = new PlanetId(501), ColoredPlanet::green());
        $this->world->savePlanet($planet_purple = new PlanetId(502), ColoredPlanet::purple());
        $this->world->savePlanet($planet_red = new PlanetId(503), ColoredPlanet::red());
        $this->world->savePlanet($homeWorld_playerThree_yellow = new PlanetId(504), ColoredPlanet::yellow());

        $armadas = new ShipCollection(
            [
                new TestShip($playerOne_transport1 = new ShipId(400), $homeWorld_playerOne_blue, 3),
            ]
        );

        $deck = $builder->createDeck();
        $this->cardRegistry = $builder->createRegistry();

        // Listeners
        {
            $publisher->addSubscriber(
                $createGameHandler = new CreateGameHandler($publisher)
            );
            $publisher->addSubscriber(
                new CollectResourcesFromPlanets($this->world, $this->players)
            );
            $publisher->addSubscriber(
                new PlayerSetup($this->players, $publisher)
            );
            $publisher->addSubscriber(
                new DrawCardHandler($this->players, $deck, GameSettings::STARTING_CARDS)
            );
            $publisher->addSubscriber(
                new StartingSpaceships(
                    $this->players,
                    GameSettings::STARTING_TRANSPORTS,
                    GameSettings::STARTING_FIGHTERS,
                    GameSettings::STARTING_CRUISERS
                )
            );
            $publisher->addSubscriber(
                new StartingCredit($this->players, GameSettings::STARTING_CREDIT)
            );
            $publisher->addSubscriber(
                new StartingDeuterium($this->players, GameSettings::STARTING_DEUTERIUM)
            );
            $publisher->addSubscriber(
                new CollectResourcesFromCrystals($this->players)
            );
            $publisher->addSubscriber(
                $endTurnHandler = new EndTurnHandler($this->players, new InProgressGame(), $publisher)
            );
            $publisher->addSubscriber(
                $selectHomeWorldHandler = new SelectHomeWorldHandler($this->players, $publisher)
            );
            $publisher->addSubscriber(
                $playCardHandler = new PlayCardHandler($this->players, $this->cardRegistry, $publisher) // todo replace with action instead
            );
            $publisher->addSubscriber(
                $moveShipHandler = new MoveShipHandler($this->players, $armadas, $this->world, $publisher)
            );
            $publisher->addSubscriber(
                $loadColonsHandler = new LoadColonsHandler($this->players, $armadas, $publisher)
            );
            $publisher->addSubscriber(
                $unloadColonsHandler = new UnloadColonsHandler($this->world, $armadas, $publisher)
            );
            $publisher->addSubscriber(
                $endPlayerTurnHandler = new EndPlayerTurnHandler($this->players, $publisher)
            );
            $publisher->addSubscriber(
                $minePlanetHandler = new MinePlanetHandler($this->players, $this->world, $publisher)
            );
            $publisher->addSubscriber(
                $colonizePlanetHandler = new ColonizePlanetHandler($this->world, GameSettings::STARTING_COLONS, $publisher)
            );
            $publisher->addSubscriber(
                $startTurnHandler = new StartPlayerTurnHandler($this->players)
            );
        }

        $this->publisher = $publisher;
    }

    /**
     * @param object $command
     */
    public function handle($command)
    {
        $this->publisher->handle($command);
    }

    /**
     * @param int $cardId
     *
     * @return Card
     */
    public function getCard(int $cardId): Card
    {
        return $this->cardRegistry->getCardWithId($cardId);
    }

    /**
     * @param PlanetId $id
     *
     * @return ReadOnlyPlanet
     */
    public function getPlanet(PlanetId $id): ReadOnlyPlanet
    {
        return $this->world->planetWithId($id);
    }

    /**
     * @param PlayerId $id
     *
     * @return ReadOnlyPlayer
     */
    public function getPlayer(PlayerId $id): ReadOnlyPlayer
    {
        return $this->players->getPlayerWithId($id);
    }

    public function enableDebug()
    {
        $this->publisher->enableDebug();
    }

    public function disableDebug()
    {
        $this->publisher->enableDebug();
    }
}
