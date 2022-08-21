<?php

declare(strict_types=1);

namespace Wqrro\duels;

class DuelHit
{

    private $player;
    private $tick;

    public function __construct(string $player, int $tick)
    {
        $this->player = $player;
        $this->tick = $tick;
    }

    public function getPlayer(): string
    {
        return $this->player;
    }

    public function equals($object): bool
    {
        $result = false;
        if ($object instanceof DuelHit) {
            //$result=abs($this->tick - $object->getTick()) >= 20;
            $result = $this->tick === $object->getTick();
        }
        return $result;
    }

    public function getTick(): int
    {
        return $this->tick;
    }
}