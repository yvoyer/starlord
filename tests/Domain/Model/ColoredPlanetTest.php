<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Exception\InvalidUsageException;

final class ColoredPlanetTest extends TestCase
{
    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(1);
    }

    public function test_it_should_colonize_the_planet()
    {
        $this->assertPlanetIsColonized(ColoredPlanet::blue());
    }

    public function test_it_should_not_allow_to_produce_resource_when_not_colonized()
    {
        $planet = ColoredPlanet::purple();
        $this->assertFalse($planet->isColonized());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot collect resources when planet is not colonized.');
        $planet->collectResources($this->player);
    }

    /**
     * @depends test_it_should_colonize_the_planet
     */
    public function test_red_planet_should_increase_attack_of_player()
    {
        $this->assertPlanetIsColonized($planet = ColoredPlanet::red());
        $this->assertSame(0, $this->player->getBaseAttack()->toInt());

        $planet->collectResources($this->player);

        $this->assertSame(1, $this->player->getBaseAttack()->toInt());
    }

    /**
     * @depends test_it_should_colonize_the_planet
     */
    public function test_purple_planet_should_increase_chance_of_finding_crystal()
    {
        $this->assertPlanetIsColonized($planet = ColoredPlanet::purple());
        $this->assertSame(0, $this->player->getMiningLevel()->toInt());

        $planet->collectResources($this->player);

        $this->assertSame(1, $this->player->getMiningLevel()->toInt());
    }

    /**
     * @depends test_it_should_colonize_the_planet
     */
    public function test_green_planet_should_increase_number_of_colon()
    {
        $this->assertPlanetIsColonized($planet = ColoredPlanet::green());
        $this->assertSame(0, $this->player->getPopulation()->toInt());

        $planet->collectResources($this->player);

        $this->assertSame(1, $this->player->getPopulation()->toInt());
    }

    /**
     * @depends test_it_should_colonize_the_planet
     */
    public function test_blue_planet_should_increase_deuterium()
    {
        $this->assertPlanetIsColonized($planet = ColoredPlanet::blue());
        $this->assertSame(0, $this->player->getDeuterium()->toInt());

        $planet->collectResources($this->player);

        $this->assertSame(1, $this->player->getDeuterium()->toInt());
    }

    /**
     * @depends test_it_should_colonize_the_planet
     */
    public function test_yellow_planet_should_increase_credit()
    {
        $this->assertPlanetIsColonized($planet = ColoredPlanet::yellow());
        $this->assertSame(0, $this->player->getCredit()->toInt());

        $planet->collectResources($this->player);

        $this->assertSame(1, $this->player->getCredit()->toInt());
    }

    /**
     * @param WriteOnlyPlanet $planet
     */
    private function assertPlanetIsColonized(WriteOnlyPlanet $planet)
    {
        $this->assertFalse($planet->isColonized());
        $planet->colonize($this->player->getIdentity(), new Colons(0));
        $this->assertTrue($planet->isColonized());
        $this->assertEquals(new PlayerId(1), $planet->ownerId());
    }

    public function test_it_should_increase_population_when_colonized_by_any_players()
    {
        $planet = ColoredPlanet::green();
        $this->assertSame(0, $planet->population()->toInt());
        $planet->colonize(new PlayerId(1), new Colons(2));
        $this->assertSame(2, $planet->population()->toInt());
        $planet->colonize(new PlayerId(2), new Colons(2));
        $this->assertSame(4, $planet->population()->toInt());
    }

    public function test_it_should_throw_exception_when_fetching_owner_on_planet_not_colonized()
    {
        $planet = ColoredPlanet::yellow();
        $this->assertFalse($planet->isColonized());

        $this->expectException(InvalidUsageException::class);
        $this->expectExceptionMessage('Planet is not colonized yet, cannot return a valid owner.');
        $planet->ownerId();
    }
}
