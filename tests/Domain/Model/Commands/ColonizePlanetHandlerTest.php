<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\HomeWorldWasSelected;
use StarLord\Domain\Events\PlanetWasColonized;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\ColoredPlanet;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;

final class ColonizePlanetHandlerTest extends TestCase
{
    /**
     * @var ColonizePlanetHandler
     */
    private $handler;

    /**
     * @var ColoredPlanet
     */
    private $planet;

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->handler = new ColonizePlanetHandler(
            new Galaxy([9 => $this->planet = ColoredPlanet::red()]),
            2,
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_colonize_the_planet_with_starting_colons()
    {
        $playerId = new PlayerId(10);
        $this->assertFalse($this->planet->isColonized());
        $this->assertSame(0, $this->planet->population()->toInt());

        $this->handler->__invoke(
            new ColonizePlanet($playerId, new PlanetId(9), new Colons(4))
        );

        $this->assertTrue($this->planet->isColonized());
        $this->assertEquals($playerId, $this->planet->ownerId());
        $this->assertSame(4, $this->planet->population()->toInt());
    }

    public function test_it_should_colonize_the_planet_with_starting_colons_when_home_world_selected()
    {
        $playerId = new PlayerId(10);
        $this->assertFalse($this->planet->isColonized());
        $this->assertSame(0, $this->planet->population()->toInt());

        $this->handler->onHomeWorldWasSelected(
            new HomeWorldWasSelected($playerId, new PlanetId(9))
        );

        $this->assertTrue($this->planet->isColonized());
        $this->assertEquals($playerId, $this->planet->ownerId());
        $this->assertSame(2, $this->planet->population()->toInt());
    }

    public function test_it_should_publish_event()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PlanetWasColonized::class));

        $this->handler->onHomeWorldWasSelected(
            new HomeWorldWasSelected(new PlayerId(10), new PlanetId(9))
        );
    }
}
