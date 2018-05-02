<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Model\Cards\CardRegistry;
use StarLord\Domain\Model\Exception\InvalidCardException;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class PlayCardHandler
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var CardRegistry
     */
    private $cards;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param CardRegistry $cards
     * @param Publisher $publisher
     */
    public function __construct(WriteOnlyPlayers $players, CardRegistry $cards, Publisher $publisher)
    {
        $this->players = $players;
        $this->cards = $cards;
        $this->publisher = $publisher;
    }

    /**
     * @param PlayCard $command
     */
    public function __invoke(PlayCard $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $cardId = $command->cardId();
        if (! $player->hasCardInHand($cardId)) {
            throw new InvalidCardException('The card "34" cannot be played since it is not in player "1" hand.');
        }

        $player->playCard($cardId);
        $card = $this->cards->getCardWithId($cardId);
        $card->whenPlayedBy($player);

        $this->players->savePlayer($command->playerId(), $player);
        $this->publisher->publish(new CardWasPlayed($cardId, $command->playerId()));
    }
}
