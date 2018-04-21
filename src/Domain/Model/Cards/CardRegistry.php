<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\Card;

interface CardRegistry
{
    /**
     * @param int $cardId
     *
     * @return Card
     * @throws EntityNotFoundException
     */
    public function getCardWithId(int $cardId): Card;
}
