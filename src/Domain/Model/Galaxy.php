<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;
use Webmozart\Assert\Assert;

final class Galaxy implements World
{
    /**
     * @var Planet[]
     */
    private $planets;

    /**
     * @param Planet[] $planets
     */
    public function __construct(array $planets)
    {
        Assert::allIsInstanceOf($planets, Planet::class);
        $this->planets = $planets;
    }

    /**
     * @return Planet[]
     */
    public function allPlanets(): array
    {
        return $this->planets;
    }

    /**
     * @param int $playerId
     *
     * @return Planet[]
     */
    public function allColonizedPlanetsOfPlayer(int $playerId): array
    {
        return array_filter($this->planets, function (Planet $planet) {
            return $planet->isColonized();
        });
    }

    /**
     * @param PlanetId $id
     *
     * @return Planet
     * @throws EntityNotFoundException
     */
    public function planetWithId(PlanetId $id): Planet
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $quantity
     *
     * @return World
     */
    public static function withPlanetCount(int $quantity): World
    {
        $planets = [];
        for ($i = 0; $i < $quantity; $i ++) {
            $planets[] = ColoredPlanet::blue();
        }

        return new self($planets);
    }
}
