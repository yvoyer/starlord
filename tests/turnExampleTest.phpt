--TEST--
Turn 1
--FILE--
<?php
use StarLord\Domain\Events\StarLordEvent;
use StarLord\Domain\Model\Bonus\CollectResourcesFromCrystals;
use StarLord\Domain\Model\Bonus\CollectResourcesFromPlanets;
use StarLord\Domain\Model\Cards\DeckBuilder;
use StarLord\Domain\Model\Cards\DeckManager;
use StarLord\Domain\Model\Commands\EndTurn;
use StarLord\Domain\Model\Commands\EndTurnHandler;
use StarLord\Domain\Model\Commands\PlayCard;
use StarLord\Domain\Model\Commands\PlayCardHandler;
use StarLord\Domain\Model\Commands\StartGame;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\GameSettings;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\Setup\StartingCardsInHand;
use StarLord\Domain\Model\Setup\GameSetup;
use StarLord\Domain\Model\Setup\PlayerSetup;
use StarLord\Domain\Model\Setup\StartingColons;
use StarLord\Domain\Model\Setup\StartingCredit;
use StarLord\Domain\Model\Setup\StartingDeuterium;
use StarLord\Domain\Model\Setup\StartingSpaceships;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

require_once __DIR__ . '/../vendor/autoload.php';

$publisher = new class() implements Publisher {
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

    public function publish(StarLordEvent $event)
    {
        $class = get_class($event);
        $method = 'on' . ucfirst(substr($class, strrpos($class, '\\') + 1));

        foreach ($this->subscribers as $subscriber) {
            $listener = get_class($subscriber);
            if (method_exists($subscriber, $method)) {
                $data = serialize($event);
                $this->logger->log("Publish event '{$data}' to listener '{$listener}'.\n");
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
$builder = new DeckBuilder();

// Cards in player 3 hands
$builder->mineCrystal(1000, 1, 'yellow', 'small');
$builder->mineCrystal(1001, 1, 'green', 'small');
$builder->buyTransport(1002);
$builder->buyTransport(1003);
$builder->buyTransport(1004);

// Cards in player 2 hands
$builder->buildColonists(1005);
$builder->mineCrystal(1006, 1, 'purple', 'small');
$builder->buyTransport(1007);
$builder->buyTransport(1008);
$builder->buyTransport(1009);

// Cards in player 1 hands
$builder->buyTransport(1010, 2);
$builder->mineCrystal(1011, 1, 'blue', 'small');
$builder->buyTransport(1012);
$builder->addFighter(1013);
$builder->addCruiser(1014);

// Other
$players = new PlayerCollection();
$world = Galaxy::withPlanetCount(10);
$deck = $builder->createDeck();

// Listeners
{
    $publisher->addSubscriber(new CollectResourcesFromPlanets($world, $players));
    $publisher->addSubscriber(new PlayerSetup($players));
    $publisher->addSubscriber(
        new StartingCardsInHand(
            $players,
            $deck,
            GameSettings::STARTING_CARDS
        )
    );
    $publisher->addSubscriber(
        new StartingSpaceships(
            $players,
            GameSettings::STARTING_TRANSPORTS,
            GameSettings::STARTING_FIGHTERS,
            GameSettings::STARTING_CRUISERS
        )
    );
    $publisher->addSubscriber(new StartingCredit($players, GameSettings::STARTING_CREDIT));
    $publisher->addSubscriber(new StartingDeuterium($players, GameSettings::STARTING_DEUTERIUM));
    $publisher->addSubscriber(new StartingColons($players, GameSettings::STARTING_COLONS));
    $publisher->addSubscriber(new DeckManager($deck));
    $publisher->addSubscriber(new CollectResourcesFromCrystals($players));
}

// Handlers
{
    $gameSetupHandler = new GameSetup($publisher);
    $playCardHandler = new PlayCardHandler($players, $publisher);
//    $loadColonistsHandler = new LoadColonistsHandler();
    $endTurnHandler = new EndTurnHandler(new InProgressGame(), $publisher);
}

function dumpPlayer(int $id, \StarLord\Domain\Model\WriteOnlyPlayers $players) {
    /**
     * @var \StarLord\Domain\Model\TestPlayer $player
     */
    $player = $players->getPlayerWithId($id);
    $data = [
        'id' => $id,
        'population' => $player->getPopulation()->toInt(),
        'credit' => $player->getCredit()->toInt(),
        'deuterium' => $player->getDeuterium()->toInt(),
        'transports' => $player->getArmada()->transports(),
        'fighters' => $player->getArmada()->fighters(),
        'cruisers' => $player->getArmada()->cruisers(),
        'crystals' => json_decode($player->getHoard()->toString()),
    ];
    var_dump(json_encode($data));
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
//    $playCardHandler(new PlayCard($playerOne, 1011)); // Mine 1 Green Cristal
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
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\PlayerSetup'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\StartingCardsInHand'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\StartingSpaceships'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\StartingCredit'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\StartingDeuterium'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:100;}}' to listener 'StarLord\Domain\Model\Setup\StartingColons'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\PlayerSetup'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\StartingCardsInHand'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\StartingSpaceships'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\StartingCredit'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\StartingDeuterium'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:200;}}' to listener 'StarLord\Domain\Model\Setup\StartingColons'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\PlayerSetup'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\StartingCardsInHand'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\StartingSpaceships'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\StartingCredit'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\StartingDeuterium'.
Publish event 'C:39:"StarLord\Domain\Events\PlayerJoinedGame":62:{a:2:{s:4:"name";s:18:"player_joined_game";s:6:"player";i:300;}}' to listener 'StarLord\Domain\Model\Setup\StartingColons'.
string(106) "{"id":100,"population":1,"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}"
string(106) "{"id":200,"population":1,"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}"
string(106) "{"id":300,"population":1,"credit":10,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}"
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:100;s:7:"card_id";i:1010;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:200;s:7:"card_id";i:1005;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:300;s:7:"card_id";i:1000;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
string(105) "{"id":100,"population":1,"credit":8,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":[]}"
string(105) "{"id":200,"population":2,"credit":8,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}"
string(125) "{"id":300,"population":1,"credit":5,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1}}}"
Turn 2
Publish event 'C:37:"StarLord\Domain\Events\TurnWasStarted":41:{a:1:{s:4:"name";s:16:"turn_was_started";}}' to listener 'StarLord\Domain\Model\Bonus\CollectResourcesFromPlanets'.
Publish event 'C:37:"StarLord\Domain\Events\TurnWasStarted":41:{a:1:{s:4:"name";s:16:"turn_was_started";}}' to listener 'StarLord\Domain\Model\Bonus\CollectResourcesFromCrystals'.
string(105) "{"id":100,"population":1,"credit":8,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":[]}"
string(105) "{"id":200,"population":2,"credit":8,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":[]}"
string(125) "{"id":300,"population":1,"credit":6,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1}}}"
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:100;s:7:"card_id";i:1011;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:200;s:7:"card_id";i:1006;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
Publish event 'C:36:"StarLord\Domain\Events\CardWasPlayed":83:{a:3:{s:4:"name";s:15:"card_was_played";s:9:"player_id";i:300;s:7:"card_id";i:1001;}}' to listener 'StarLord\Domain\Model\Cards\DeckManager'.
string(123) "{"id":100,"population":1,"credit":3,"deuterium":5,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}"
string(125) "{"id":200,"population":2,"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}"
string(145) "{"id":300,"population":1,"credit":1,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}"
Turn 3
Publish event 'C:37:"StarLord\Domain\Events\TurnWasStarted":41:{a:1:{s:4:"name";s:16:"turn_was_started";}}' to listener 'StarLord\Domain\Model\Bonus\CollectResourcesFromPlanets'.
Publish event 'C:37:"StarLord\Domain\Events\TurnWasStarted":41:{a:1:{s:4:"name";s:16:"turn_was_started";}}' to listener 'StarLord\Domain\Model\Bonus\CollectResourcesFromCrystals'.
string(123) "{"id":100,"population":1,"credit":3,"deuterium":6,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}"
string(125) "{"id":200,"population":2,"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}"
string(145) "{"id":300,"population":2,"credit":2,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}"
string(123) "{"id":100,"population":1,"credit":3,"deuterium":6,"transports":4,"fighters":1,"cruisers":0,"crystals":{"blue":{"small":1}}}"
string(125) "{"id":200,"population":2,"credit":3,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"purple":{"small":1}}}"
string(145) "{"id":300,"population":2,"credit":2,"deuterium":5,"transports":2,"fighters":1,"cruisers":0,"crystals":{"yellow":{"small":1},"green":{"small":1}}}"
End of game
