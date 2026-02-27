<?php

declare(strict_types=1);

namespace App\Scoreboard\Model;

final class Game
{
    public function __construct(
        public readonly Team $homeTeam,
        public readonly Team $awayTeam,
        private int $homeScore = 0,
        private int $awayScore = 0,
        public readonly int $startedOrder = 0,
    ) {}

    public function homeScore(): int
    {
        return $this->homeScore;
    }

    public function awayScore(): int
    {
        return $this->awayScore;
    }

    public function totalScore(): int
    {
        return $this->homeScore + $this->awayScore;
    }

    public function updateScore(int $homeScore, int $awayScore): void
    {
        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;
    }

    public function toSummaryString(): string
    {
        return sprintf(
            '%s %d - %s %d',
            $this->homeTeam->name(),
            $this->homeScore,
            $this->awayTeam->name(),
            $this->awayScore
        );
    }
}
