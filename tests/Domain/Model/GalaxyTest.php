<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;

final class GalaxyTest extends TestCase
{
    public function test_it_should_return_the_planets_colonized_by_player()
    {
        $galaxy = Galaxy::withPlanetCount(3);
        $this->assertCount(3, $planets = $galaxy->allPlanets());

        $planets[0]->colonize(new PlayerId(1), new Colons(0));
        $this->assertCount(1, $galaxy->allColonizedPlanetsOfPlayer(1));
    }

    public function test_it_should_return_the_planet_by_id()
    {
        $galaxy = new Galaxy([]);
        $galaxy->savePlanet($planetId = new PlanetId(1), $planet = $this->createMock(WriteOnlyPlanet::class));
        $this->assertSame($planet, $galaxy->planetWithId($planetId));
    }

    /**
     * @expectedException        \Star\Component\Identity\Exception\EntityNotFoundException
     * @expectedExceptionMessage Object of class 'StarLord\Domain\Model\WriteOnlyPlanet' with identity '2' could not be found.
     */
    public function test_it_should_throw_exception_when_planet_not_found()
    {
        Galaxy::withPlanetCount(0)->planetWithId(new PlanetId(2));
    }
}
