<?php

declare(strict_types=1);

namespace Wqrro\duels;

use Wqrro\Utils;
use pocketmine\level\Level;
use pocketmine\level\Position;

class PracticeArena
{

    public const DUEL_ARENA = "arena.duel";
    public const NO_ARENA = "none";
    protected $level;
    private $name;
    private $arenaType;
    private $spawnPos;

    private $build;

    public function __construct(string $name, string $arenaType, bool $canBuild, Position $center)
    {
        $this->name = $name;
        $this->arenaType = $arenaType;
        $this->build = $canBuild;
        $this->spawnPos = $center;
        $this->level = $center->getLevel();
    }

    public static function getType(string $test): string
    {
        $result = "unknown";
        if (Utils::equals_string($test, "duel", "duels", "Duels", "Duel", "DUEL", "1vs1", "1v1")) {
            $result = self::DUEL_ARENA;
        }
        return $result;
    }

    public static function getFormattedType(string $type): string
    {
        $str = "(Unknown)";
        if ($type === self::DUEL_ARENA) {
            $str = "(Duel)";
        }
        return $str;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function getSpawnPosition(): Position
    {
        return $this->spawnPos;
    }

    public function canBuild(): bool
    {
        return $this->build;
    }

    public function getLocalizedName(): string
    {
        return strtolower(strval(str_replace(" ", "", $this->name)));
    }

    public function equals($arena): bool
    {
        $result = false;
        if ($arena instanceof PracticeArena) {
            if ($arena->getArenaType() === $this->arenaType) {
                $result = $arena->getName() === $this->name;
            }
        }
        return $result;
    }

    public function getArenaType(): string
    {
        return $this->arenaType;
    }

    public function getName(): string
    {
        return $this->name;
    }
    //public abstract function toMap():array;
}