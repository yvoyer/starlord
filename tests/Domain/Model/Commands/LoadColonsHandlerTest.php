<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\ColonsWereLoaded;
use StarLord\Domain\Model\Exception\CapacityException;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Model\Testing\TestShip;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;
use StarLord\Infrastructure\Persistence\InMemory\ShipCollection;

final class LoadColonsHandlerTest extends TestCase
{
    /**
     * @var LoadColonsHandler
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
     * @var ShipCollection
     */
    private $armada;

    /**
     * @var TestShip
     */
    private $ship;

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->players = new PlayerCollection([$this->player = TestPlayer::fromInt(1)]);
        $this->armada = new ShipCollection([$this->ship = TestShip::transport(5, 10)]);
        $this->publisher = $this->createMock(Publisher::class);
        $this->handler = new LoadColonsHandler(
            $this->players,
            $this->armada,
            $this->publisher
        );
    }

    /**
     * @expectedException        \StarLord\Domain\Model\Exception\MissingColonsException
     * @expectedExceptionMessage Player with id "1" do not have the required colons. Requires at least "1", got "0".
     */
    public function test_it_should_not_allow_to_load_colon_when_player_do_not_have_all_required_colons()
    {
        $quantity = 1;
        $this->assertTrue($this->player->availableColons()->lowerThan($quantity));
        $this->handler->__invoke(new LoadColons($this->player->getIdentity(), $quantity, $this->ship->getIdentity()));
    }

    public function test_it_should_load_colon_in_ship()
    {
        $this->player->addColons(5);
        $this->assertSame(5, $this->player->availableColons()->toInt());
        $this->assertSame(3, $this->ship->remainingCapacity()->toInt());
        $this->assertSame(0, $this->ship->getColons()->toInt());

        $this->handler->__invoke(new LoadColons($this->player->getIdentity(), 2, $this->ship->getIdentity()));

        $this->assertSame(3, $this->player->availableColons()->toInt());
        $this->assertSame(1, $this->ship->remainingCapacity()->toInt());
        $this->assertSame(2, $this->ship->getColons()->toInt());
    }

    public function test_it_should_not_allow_to_load_colon_when_ship_do_not_support_transport()
    {
        $this->player->addColons(5);
        $ship = TestShip::fighter(1, 1);
        $this->armada->saveShip($ship);
        $this->assertSame(0, $ship->remainingCapacity()->toInt());

        $this->expectException(CapacityException::class);
        $this->expectExceptionMessage(
            'Ship do not allow to exceed of "0", tried to load 1 colons with actual capacity "0/0".'
        );
        $this->handler->__invoke(new LoadColons($this->player->getIdentity(), 1, $ship->getIdentity()));
    }

    public function test_it_should_not_allow_to_load_colon_when_ship_is_full()
    {
        $this->player->addColons(5);
        $ship = TestShip::cruiser(1, 1);
        $this->armada->saveShip($ship);
        $this->assertSame(1, $ship->remainingCapacity()->toInt());

        $this->expectException(CapacityException::class);
        $this->expectExceptionMessage(
            'Ship do not allow to exceed of "1", tried to load 2 colons with actual capacity "0/1".'
        );
        $this->handler->__invoke(new LoadColons($this->player->getIdentity(), 2, $ship->getIdentity()));
    }

    public function test_it_should_publish_event_when_loading_ship()
    {
        $this->player->addColons(5);
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(ColonsWereLoaded::class));

        $this->handler->__invoke(
            new LoadColons($this->player->getIdentity(), 2, $this->ship->getIdentity())
        );
    }
}
