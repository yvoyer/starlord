<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\UserActionStore;

final class ColonizePlanetTest extends TestCase
{
    /**
     * @var ColonizePlanet
     */
    private $card;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(1);
        $this->player->addCredit(new Credit(10));
        $this->card = new ColonizePlanet(new Credit(2));
    }

    public function test_it_initialize_colonization()
    {
        $this->assertEmpty($this->player->actionsToPerform());

        $this->card->whenPlayedBy($this->player);

        $this->assertCount(1, $this->player->actionsToPerform());
        $this->assertEquals([UserActionStore::MOVE_SHIP], $this->player->actionsToPerform());
    }

    public function test_it_should_cost_credit_to_colonize()
    {
        $this->assertSame(10, $this->player->getCredit()->toInt());

        $this->card->whenPlayedBy($this->player);

        $this->assertSame(8, $this->player->getCredit()->toInt());
    }
}
