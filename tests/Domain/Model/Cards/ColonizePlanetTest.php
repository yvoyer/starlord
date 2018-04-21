<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class ColonizePlanetTest extends TestCase
{
    /**
     * @var ColonizePlanet
     */
    private $card;

    /**
     * @var WriteOnlyPlayer|MockObject
     */
    private $player;

    public function setUp()
    {
        $this->player = $this->createMock(WriteOnlyPlayer::class);
        $this->card = new ColonizePlanet(new Credit(2));
    }

    public function test_it_initialize_colonization()
    {
        $this->player
            ->expects($this->once())
            ->method('startAction')
            ->with([UserActionStore::MOVE_SHIP]);

        $this->card->whenPlayedBy($this->player);
    }

    public function test_it_should_cost_credit_to_colonize()
    {
        $this->player
            ->expects($this->once())
            ->method('pay')
            ->with(new Credit(2));

        $this->card->whenPlayedBy($this->player);
    }
}
