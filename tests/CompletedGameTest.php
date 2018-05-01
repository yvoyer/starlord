<?php declare(strict_types=1);

namespace StarLord;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;
use StarLord\Application\StarLordGame;
use StarLord\Domain\Model\Commands\CreateGame;
use StarLord\Domain\Model\Commands\EndPlayerTurn;
use StarLord\Domain\Model\Commands\MinePlanet;
use StarLord\Domain\Model\Commands\PlayCard;
use StarLord\Domain\Model\Commands\SelectHomeWorld;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ReadOnlyPlayer;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class CompletedGameTest extends TestCase
{
    /**
     * @var PlayerId
     */
    private $playerOne;

    /**
     * @var PlayerId
     */
    private $playerTwo;

    /**
     * @var PlayerId
     */
    private $playerThree;

    /**
     * @var PlanetId
     * Blue planet
     * HomeWorld of player one
     */
    private $planetOne;

    /**
     * @var PlanetId
     * Green planet
     * HomeWorld of player two
     */
    private $planetTwo;

    /**
     * @var PlanetId
     * Purple planet
     */
    private $planetThree;

    /**
     * @var PlanetId
     * Red planet
     */
    private $planetFour;

    /**
     * @var PlanetId
     * Yellow planet
     * HomeWorld of player three
     */
    private $planetFive;

    public function setUp()
    {
        $this->playerOne = new PlayerId(100);
        $this->playerTwo = new PlayerId(200);
        $this->playerThree = new PlayerId(300);

        $this->planetOne = new PlanetId(500);
        $this->planetTwo = new PlanetId(501);
        $this->planetThree = new PlanetId(502);
        $this->planetFour = new PlanetId(503);
        $this->planetFive = new PlanetId(504);

    }

    public function test_start_of_game()
    {
        $game = new StarLordGame(
            $players = new PlayerCollection([])
        );
        $game->handle(new CreateGame([$this->playerOne, $this->playerTwo, $this->playerThree]));

        $game->handle(new SelectHomeWorld($this->playerOne, $this->planetOne));
        $game->handle(new EndPlayerTurn($this->playerOne));

        $game->handle(new SelectHomeWorld($this->playerTwo, $this->planetTwo));
        $game->handle(new EndPlayerTurn($this->playerTwo));

        $game->handle(new SelectHomeWorld($this->playerThree, $this->planetFive));
        $game->handle(new EndPlayerTurn($this->playerThree));

        AssertThat::player($game->getPlayer($this->playerOne))
            ->hasPopulation(1)
            ->hasCredit(10)
        ;
//                ->hasDeuterium(5)
  //              ->hasCrystalCount(0)
    //            ->hasCruiserCount(0)
      //          ->hasTransportCount(1)
        //        ->hasFighterCount(2)
          //      ->hasCardsInHand([1010, 1011, 1012, 1013, 1014])
//        $this->assertPlayer($this->playerTwo, $game);
  //      $this->assertPlayer($this->playerThree, $game);

        $game->handle(new PlayCard($this->playerOne, 1010)); // Buy 2 transport for 1 CRD
        $game->handle(new PlayCard($this->playerTwo, 1005)); // Buy Colonists for 2CRD todo produced on start of turn maybe??
        $game->handle(new PlayCard($this->playerThree, 1000)); // Mine Yellow Crystal (on starting planet) for 5 CRD
        $game->handle(new MinePlanet($this->playerThree, $this->planetFive));

//    $endPlayerTurnHandler(new EndPlayerTurn($playerOne));
        //  $endPlayerTurnHandler(new EndPlayerTurn($playerTwo));
        //$endPlayerTurnHandler(new EndPlayerTurn($playerThree));

        $this->assertPlayer($this->playerOne, $game);
        $this->assertPlayer($this->playerTwo, $game);
        $this->assertPlayer($this->playerThree, $game);

        $this->fail('ICI');
    }

//{ echo "Turn 2\n";
//assertPlayer($playerOne, $players);
//assertPlayer($playerTwo, $players);
//assertPlayer($playerThree, $players);
//
//$playCardHandler(new PlayCard($playerOne, 1011)); // Mine 1 Green Cristal
//$playCardHandler(new PlayCard($playerTwo, 1006)); // Mine 1 Purple Cristal
//$playCardHandler(new PlayCard($playerThree, 1001)); // Mine 1 Blue Cristal
//
//assertPlayer($playerOne, $players);
//assertPlayer($playerTwo, $players);
//assertPlayer($playerThree, $players);
//}
//
//    { echo "Turn 3\n";
//        assertPlayer($playerOne, $players);
//        assertPlayer($playerTwo, $players);
//        assertPlayer($playerThree, $players);
//
//// todo colonize, draw cards at start of turn, move colons to planet
//        $playCardHandler(new PlayCard($playerOne, 1012)); // Colonize planet
//        $loadColonsHandler(new LoadColons($playerOne, 2, $playerOne_transport1));
//        $moveShipHandler(new MoveShip($playerOne, $playerOne_transport1, $hoplanetOne_homeworld_blue));
//        $unloadColonsHandler(new UnloadColons($playerOne, $playerOne_transport1, 2));
//        $endPlayerTurnHandler(new EndPlayerTurn($playerOne));
//
////    $performActionHandler(new PerformAction($playerOne, $a_moveShip->name()));
//        //  $performActionHandler(new PerformAction($playerOne, $a_unloadColons->name()));
//
////    $playCardHandler(new PlayCard($playerTwo, 1006)); // Mine 1 Purple Cristal
////    $playCardHandler(new PlayCard($playerThree, 1001)); // Mine 1 Blue Cristal
//
//        assertPlayer($playerOne, $players);
//        assertPlayer($playerTwo, $players);
//        assertPlayer($playerThree, $players);
//    }
    private function assertPlayer(ReadOnlyPlayer $player, Constraint $constraint) {
        $data = [
            'id' => $player->getIdentity()->toInt(),
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

        $this->assertThat(json_encode($data), $constraint);
    }
}
