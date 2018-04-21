<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlanetWasMined;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\ColoredPlanet;
use StarLord\Domain\Model\Exception\InvalidPlanetOwnerException;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class MinePlanetHandlerTest extends TestCase
{
    /**
     * @var MinePlanetHandler
     */
    private $handler;

    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var Galaxy
     */
    private $world;

    /**
     * @var ColoredPlanet
     */
    private $planet;

    /**
     * @var PlanetId
     */
    private $planetId;

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->world = new Galaxy([]);
        $this->world->savePlanet($this->planetId = new PlanetId(10), $this->planet = ColoredPlanet::yellow());
        $this->player = TestPlayer::fromInt(1);
        $this->player->startAction([UserActionStore::MINE_PLANET]);

        $this->handler = new MinePlanetHandler(
            $this->players = new PlayerCollection([$this->player]),
            $this->world,
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_add_small_crystal_on_planet()
    {
        $this->assertPlanetIsColonized();
        $this->assertEquals($this->player->getIdentity(), $this->planet->ownerId());
        $this->assertCount(0, $this->planet->stash()->crystals());

        $this->handler->__invoke(new MinePlanet($this->player->getIdentity(), $this->planetId));

        $this->assertCount(1, $this->planet->stash()->crystals());
        $this->assertSame(1, $this->planet->stash()->ofSize('small'));
        $this->assertSame(1, $this->planet->stash()->ofColor('yellow'));
    }

    public function test_it_not_allow_to_mine_a_planet_you_do_not_own()
    {
        $other = TestPlayer::fromInt(99);
        $other->startAction([UserActionStore::MINE_PLANET]);
        $this->players->savePlayer($other->getIdentity(), $other);
        $this->assertPlanetIsColonized();
        $this->expectException(InvalidPlanetOwnerException::class);
        $this->expectExceptionMessage('Cannot mine a planet that you do not own.');

        $this->handler->__invoke(new MinePlanet($other->getIdentity(), $this->planetId));
    }

    public function test_it_not_allow_to_mine_a_planet_no_one_colonized()
    {
        $this->assertFalse($this->planet->isColonized());
        $this->expectException(InvalidPlanetOwnerException::class);
        $this->expectExceptionMessage('Cannot mine a planet that was never colonized.');

        $this->handler->__invoke(new MinePlanet($this->player->getIdentity(), $this->planetId));
    }

    public function test_it_publish_event_when_planet_is_mined()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PlanetWasMined::class));

        $this->assertPlanetIsColonized();

        $this->handler->__invoke(new MinePlanet($this->player->getIdentity(), $this->planetId));
    }

    private function assertPlanetIsColonized()
    {
        $this->planet->colonize($this->player->getIdentity(), new Colons(0));
    }
}
