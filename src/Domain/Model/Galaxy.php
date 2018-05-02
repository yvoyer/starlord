<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;
use Webmozart\Assert\Assert;

final class Galaxy implements World
{
    /**
     * @var WriteOnlyPlanet[]
     */
    private $planets;

    /**
     * @param WriteOnlyPlanet[] $planets
     */
    public function __construct(array $planets)
    {
        Assert::allIsInstanceOf($planets, WriteOnlyPlanet::class);
        array_map(
            function (int $key) use ($planets) {
                $this->savePlanet(new PlanetId($key), $planets[$key]);
            },
            array_keys($planets)
        );
    }

    /**
     * @return WriteOnlyPlanet[]
     */
    public function allPlanets(): array
    {
        return $this->planets;
    }

    /**
     * @param PlayerId $playerId
     *
     * @return WriteOnlyPlanet[]
     */
    public function allColonizedPlanetsOfPlayer(PlayerId $playerId): array
    {
        return array_filter($this->planets, function (WriteOnlyPlanet $planet) use ($playerId) {
            return $planet->isColonizedBy($playerId);
        });
    }

    /**
     * @param PlanetId $id
     *
     * @return WriteOnlyPlanet
     * @throws EntityNotFoundException
     */
    public function planetWithId(PlanetId $id): WriteOnlyPlanet
    {
        if (! isset($this->planets[$id->toString()])) {
            throw EntityNotFoundException::objectWithIdentity($id);
        }

        return $this->planets[$id->toString()];
    }

    /**
     * @param PlanetId $id
     * @param WriteOnlyPlanet $planet
     */
    public function savePlanet(PlanetId $id, WriteOnlyPlanet $planet)
    {
        $this->planets[$id->toString()] = $planet;
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
