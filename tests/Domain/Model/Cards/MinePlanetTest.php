<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\UserActionStore;

final class MinePlanetTest extends TestCase
{
    public function test_it_should_cost_credit()
    {
        $player = TestPlayer::fromInt(1);
        $player->addCredit(new Credit(10));
        $card = new MinePlanet(1);
        $player->drawCard(1, $card);

        $this->assertSame(10, $player->getCredit()->toInt());

        $card->whenPlayedBy($player);

        $this->assertSame(5, $player->getCredit()->toInt());
    }

    public function test_it_should_add_actions_when_played()
    {
        $player = TestPlayer::fromInt(1);
        $player->addCredit(new Credit(10));
        $card = new MinePlanet(1);
        $player->drawCard(1, $card);

        $this->assertCount(0, $player->actionsToPerform());

        $card->whenPlayedBy($player);

        $this->assertCount(1, $player->actionsToPerform());
        $this->assertEquals([UserActionStore::MINE_PLANET], $player->actionsToPerform());
    }
}
