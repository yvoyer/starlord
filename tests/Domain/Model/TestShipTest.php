<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;

final class TestShipTest extends TestCase
{
    /**
     * @var TestShip
     */
    private $ship;

    public function setUp()
    {
        $this->ship = new TestShip(new ShipId(12), new PlanetId(1), 0);
    }

    public function test_it_should_move_ship_to_another_location()
    {
        $this->assertEquals(new PlanetId(1), $this->ship->locationId());
        $this->ship->moveTo(new PlanetId(2));
        $this->assertEquals(new PlanetId(2), $this->ship->locationId());
    }
}
