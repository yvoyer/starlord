--TEST--
Example game
--FILE--
<?php
use StarLord\Domain\Model\Commands\EndPlayerTurn;
use StarLord\Domain\Model\Commands\EndTurn;
use StarLord\Domain\Model\Commands\LoadColons;
use StarLord\Domain\Model\Commands\MoveShip;
use StarLord\Domain\Model\Commands\PlayCard;
use StarLord\Domain\Model\Commands\StartGame;
use StarLord\Domain\Model\Commands\UnloadColons;
use StarLord\Domain\Model\GameSettings;

require_once __DIR__ . '/../vendor/autoload.php';

$publisher = new class() implements \StarLord\Domain\Model\Publisher {
    private $subscribers = [];
    private $logger;

    public function __construct()
    {
        $this->logger = new class () {
            public function log(string $message) {
                echo($message);
            }
        };
    }

    public function addSubscriber($subscriber) {
        $this->subscribers[] = $subscriber;
    }

    public function publish(\StarLord\Domain\Events\StarLordEvent $event)
    {
        $class = get_class($event);
        $method = 'on' . ucfirst(substr($class, strrpos($class, '\\') + 1));

        foreach ($this->subscribers as $subscriber) {
            $listener = get_class($subscriber);
            if (method_exists($subscriber, $method)) {
                $data = $event->serialize();
                $this->logger->log("Publish event: '{$data}'.\n");
                $subscriber->{$method}($event);
            } else {
//                $this->logger->log("Skip listener '{$listener}' for event: '{$class}'. \n");
            }
        }
    }
};

$playerOne = 100;
$playerTwo = 200;
$playerThree = 300;

// Cards
$builder = new \StarLord\Domain\Model\Cards\DeckBuilder();

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
$builder->mineCrystal(1001, 1, 'green', 'small'); // turn 2
$builder->mineCrystal(1000, 1, 'yellow', 'small'); // turn 1

// Cards in player 2 hands
$builder->addPendingCard(1009);
$builder->addPendingCard(1008);
$builder->addPendingCard(1007);
$builder->mineCrystal(1006, 1, 'purple', 'small'); // turn 2
$builder->buildColonists(1005); // turn 1

// Cards in player 1 hands
$builder->addPendingCard(1014);
$builder->addPendingCard(1013);
$builder->colonizePlanet(1012); // turn 3
$builder->mineCrystal(1011, 1, 'blue', 'small'); // turn 2
$builder->buyTransport(1010, 2); // turn 1

// Other
$armadas = new \StarLord\Infrastructure\Persistence\InMemory\ShipCollection([]);
$players = new \StarLord\Infrastructure\Persistence\InMemory\PlayerCollection();
$world = \StarLord\Domain\Model\Galaxy::withPlanetCount(10);
$deck = $builder->createDeck();
$actions = new \StarLord\Infrastructure\Persistence\InMemory\ActionRegistry([]);
//    [
//        $a_moveShip = new \StarLord\Domain\Model\Actions\MoveShipAction(),
//        $a_loadColons = new \StarLord\Domain\Model\Actions\MoveShipToPlanet(),
//        $a_unloadColons = new \StarLord\Domain\Model\Actions\MoveShipToPlanet(),
//    ]

// Handlers
{
    $gameSetupHandler = new \StarLord\Domain\Model\Setup\GameSetup($publisher);
    $playCardHandler = new \StarLord\Domain\Model\Commands\PlayCardHandler($players, $publisher);
//    $loadColonistsHandler = new LoadColonistsHandler();
    $endTurnHandler = new \StarLord\Domain\Model\Commands\EndTurnHandler(
            $players,
            new \StarLord\Domain\Model\InProgressGame(),
            $publisher
    );
//    $performActionHandler = new \StarLord\Domain\Model\Commands\PerformActionHandlerTest($players, $actions, $publisher);
    $moveShipHandler = new \StarLord\Domain\Model\Commands\MoveShipHandler($players, $armadas, $world);
    $loadColonsHandler = new \StarLord\Domain\Model\Commands\LoadColonsHandler($players, $armadas, $publisher);
    $unloadColonsHandler = new \StarLord\Domain\Model\Commands\UnloadColonsHandler();
    $endPlayerTurnHandler = new \StarLord\Domain\Model\Commands\EndPlayerTurnHandler($players, $publisher);
}

// Listeners
{
    $publisher->addSubscriber(new \StarLord\Domain\Model\Bonus\CollectResourcesFromPlanets($world, $players));
    $publisher->addSubscriber(new \StarLord\Domain\Model\Setup\PlayerSetup($players));
    $publisher->addSubscriber(new \StarLord\Domain\Model\Commands\DrawCardHandler($players, $deck, GameSettings::STARTING_CARDS));
    $publisher->addSubscriber(
        new \StarLord\Domain\Model\Setup\StartingSpaceships(
            $players,
            GameSettings::STARTING_TRANSPORTS,
            GameSettings::STARTING_FIGHTERS,
            GameSettings::STARTING_CRUISERS
        )
    );
    $publisher->addSubscriber(new \StarLord\Domain\Model\Setup\StartingCredit($players, GameSettings::STARTING_CREDIT));
    $publisher->addSubscriber(new \StarLord\Domain\Model\Setup\StartingDeuterium($players, GameSettings::STARTING_DEUTERIUM));
    $publisher->addSubscriber(new \StarLord\Domain\Model\Setup\StartingColons($players, GameSettings::STARTING_COLONS));
    $publisher->addSubscriber(new \StarLord\Domain\Model\Bonus\CollectResourcesFromCrystals($players));
    $publisher->addSubscriber($endTurnHandler);
}

function dumpPlayer(int $id, \StarLord\Domain\Model\WriteOnlyPlayers $players) {
    /**
     * @var \StarLord\Domain\Model\TestPlayer $player
     */
    $player = $players->getPlayerWithId($id);
    $data = [
        'id' => $id,
        'population' => $player->getPopulation()->toInt(),
        'hand' => $player->cards(),
        'credit' => $player->getCredit()->toInt(),
        'deuterium' => $player->getDeuterium()->toInt(),
        'transports' => $player->getArmada()->transports(),
        'fighters' => $player->getArmada()->fighters(),
        'cruisers' => $player->getArmada()->cruisers(),
        'crystals' => json_decode($player->getHoard()->toString()),
        // todo add colonized planets
        // todo add representation of the planets repartition (who has how many colonies)
    ];
    echo(json_encode($data) . "\n");
}

{ echo "Start of game\n";
    $gameSetupHandler(new StartGame([$playerOne, $playerTwo, $playerThree]));
    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);

    $playCardHandler(new PlayCard($playerOne, 1010)); // Buy 2 transport for 1 CRD
    $playCardHandler(new PlayCard($playerTwo, 1005)); // Buy Colonists for 2CRD
    $playCardHandler(new PlayCard($playerThree, 1000)); // Mine Yellow Crystal for 5 CRD

    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);
}

{ echo "Turn 2\n";
    $endTurnHandler(new EndTurn());
    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);

    $playCardHandler(new PlayCard($playerOne, 1011)); // Mine 1 Green Cristal
    $playCardHandler(new PlayCard($playerTwo, 1006)); // Mine 1 Purple Cristal
    $playCardHandler(new PlayCard($playerThree, 1001)); // Mine 1 Blue Cristal

    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);
}

{ echo "Turn 3\n";
    $endTurnHandler(new EndTurn());
    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);

// todo colonize, draw cards at start of turn, move colons to planet
    $playCardHandler(new PlayCard($playerOne, 1012)); // Colonize planet
    $loadColonsHandler(new LoadColons($playerOne, 2, $t1_playerOne));
    $moveShipHandler(new MoveShip($playerOne, $t1_playerOne, $planet1));
    $unloadColonsHandler(new UnloadColons($playerOne, 2, $planet2));
    $endPlayerTurnHandler(new EndPlayerTurn($playerOne));

//    $performActionHandler(new PerformAction($playerOne, $a_moveShip->name()));
  //  $performActionHandler(new PerformAction($playerOne, $a_unloadColons->name()));

//    $playCardHandler(new PlayCard($playerTwo, 1006)); // Mine 1 Purple Cristal
//    $playCardHandler(new PlayCard($playerThree, 1001)); // Mine 1 Blue Cristal

    dumpPlayer($playerOne, $players);
    dumpPlayer($playerTwo, $players);
    dumpPlayer($playerThree, $players);
}

echo "End of game\n";
?>
--EXPECTF--
Start of game
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":100}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":200}'.
Publish event: '{"name":"player_joined_game","player":300}'.
Publish event: '{"name":"player_joined_game","player":300}'.
Publish event: '{"name":"player_joined_game","player":300}'.
Publish event: '{"name":"player_joined_game","player":300}'.
Publish event: '{"name":"player_joined_game","player":300}'.
Publish event: '{"name":"player_joined_game","player":300}'.
{"id":100,"population":1,"hand":[1010,1011,1012,1013,1014],"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}
{"id":200,"population":1,"hand":[1005,1006,1007,1008,1009],"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}
{"id":300,"population":1,"hand":[1000,1001,1002,1003,1004],"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}
Publish event: '{"name":"card_was_played","player_id":100,"card_id":1010}'.
Publish event: '{"name":"card_was_played","player_id":200,"card_id":1005}'.
Publish event: '{"name":"card_was_played","player_id":300,"card_id":1000}'.
{"id":100,"population":1,"hand":[1011,1012,1013,1014,1015],"credit":8,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":[]}
{"id":200,"population":2,"hand":[1006,1007,1008,1009,1016],"credit":8,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}
{"id":300,"population":1,"hand":[1001,1002,1003,1004,1017],"credit":5,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1}}}
Turn 2
Publish event: '{"name":"turn_was_started"}'.
Publish event: '{"name":"turn_was_started"}'.
{"id":100,"population":1,"hand":[1011,1012,1013,1014,1015],"credit":8,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":[]}
{"id":200,"population":2,"hand":[1006,1007,1008,1009,1016],"credit":8,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}
{"id":300,"population":1,"hand":[1001,1002,1003,1004,1017],"credit":6,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1}}}
Publish event: '{"name":"card_was_played","player_id":100,"card_id":1011}'.
Publish event: '{"name":"card_was_played","player_id":200,"card_id":1006}'.
Publish event: '{"name":"card_was_played","player_id":300,"card_id":1001}'.
{"id":100,"population":1,"hand":[1012,1013,1014,1015,1018],"credit":3,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}
{"id":200,"population":2,"hand":[1007,1008,1009,1016,1019],"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}
{"id":300,"population":1,"hand":[1002,1003,1004,1017,1020],"credit":1,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}
Turn 3
Publish event: '{"name":"turn_was_started"}'.
Publish event: '{"name":"turn_was_started"}'.
{"id":100,"population":1,"hand":[1012,1013,1014,1015,1018],"credit":3,"deuterium":6,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}
{"id":200,"population":2,"hand":[1007,1008,1009,1016,1019],"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}
{"id":300,"population":2,"hand":[1002,1003,1004,1017,1020],"credit":2,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}
Publish event: '{"name":"card_was_played","player_id":100,"card_id":1012}'.
// wait for player to choose ship to send and how many colonists
// wait for player to choose a planet to colonize
// postpone payment of deuterium when planet is chosen to pay for each sent ships
// when all players are in done mode end turn
// should switch all player to pending at start of turn
{"id":100,"population":1,"hand":[1013,1014,1015,1018,1021],"credit":1,"deuterium":4,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}
{"id":200,"population":2,"hand":[1007,1008,1009,1016,1019],"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}
{"id":300,"population":2,"hand":[1002,1003,1004,1017,1020],"credit":2,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}
End of game
