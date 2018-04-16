<?php //declare(strict_types=1);
//
//namespace StarLord\Domain\Model\Actions;
//
//use PHPUnit\Framework\TestCase;
//use StarLord\Domain\Model\PlanetId;
//use StarLord\Domain\Model\TestPlayer;
//use StarLord\Infrastructure\Model\Testing\TestShip;
//
//final class MoveShipActionTest extends TestCase
//{
//    public function test_it_should_move_ship_to_new_destination()
//    {
//        $player = TestPlayer::fromInt(1);
//        $action = new MoveShipAction(
//            $ship = TestShip::fromInt(6, 99),
//            $planet = new PlanetId(32)
//        );
//
//        $this->assertFalse($ship->isDocked($planet));
//
//        $action->perform($player);
//
//        $this->assertTrue($ship->isDocked($planet));
//    }
//}
