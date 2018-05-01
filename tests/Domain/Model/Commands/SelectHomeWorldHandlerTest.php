<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\HomeWorldWasSelected;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class SelectHomeWorldHandlerTest extends TestCase
{
    /**
     * @var SelectHomeWorldHandler
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->handler = new SelectHomeWorldHandler(
            new PlayerCollection([$this->player = TestPlayer::playingPlayer(1)]),
            $this->publisher = $this->createMock(Publisher::class)
        );
        $this->player->startAction(new InProgressGame(), [UserActionStore::SELECT_HOME_WORLD]);
    }

    public function test_it_should_perform_the_action()
    {
        $this->assertEquals([UserActionStore::SELECT_HOME_WORLD], $this->player->actionsToPerform());

        $this->handler->__invoke(
            new SelectHomeWorld($this->player->getIdentity(), new PlanetId(9))
        );

        $this->assertCount(0, $this->player->actionsToPerform());
    }

    public function test_it_should_publish_the_event()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(HomeWorldWasSelected::class));

        $this->handler->__invoke(
            new SelectHomeWorld($this->player->getIdentity(), new PlanetId(9))
        );
    }
}
