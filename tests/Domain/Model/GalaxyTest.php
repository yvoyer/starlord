<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;

final class GalaxyTest extends TestCase
{
    public function test_it_should_return_the_colonized_planets()
    {
        $galaxy = Galaxy::withPlanetCount(3);
        $this->assertCount(3, $planets = $galaxy->allPlanets());

        $owner = new TestPlayer(1);
        $planets[0]->colonize($owner);
        $this->assertCount(1, $galaxy->allColonizedPlanetsOfPlayer(1));
    }
}
