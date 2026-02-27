<?php

declare(strict_types=1);

namespace App\Scoreboard;

use App\Scoreboard\Exception\ScoreboardException;
use App\Scoreboard\Model\Game;

final class Scoreboard
{
    /** @var array<string, Game> */
    private array $games = [];

    private int $orderCounter = 0;

    public function startGame(string $homeTeam, string $awayTeam): void
    {
        $homeTeam = trim($homeTeam);
        $awayTeam = trim($awayTeam);

        if ($homeTeam === '' || $awayTeam === '') {
            throw new ScoreboardException('Team names must not be empty.');
        }
        if (mb_strtolower($homeTeam) === mb_strtolower($awayTeam)) {
            throw new ScoreboardException('Home and away team must be different.');
        }

        $key = $this->key($homeTeam, $awayTeam);
        if (isset($this->games[$key])) {
            throw new ScoreboardException('This game is already in progress.');
        }

        $this->orderCounter++;
        $this->games[$key] = new Game(
            homeTeam: $homeTeam,
            awayTeam: $awayTeam,
            homeScore: 0,
            awayScore: 0,
            startedOrder: $this->orderCounter
        );
    }

    public function finishGame(string $homeTeam, string $awayTeam): void
    {
        $key = $this->key($homeTeam, $awayTeam);
        if (!isset($this->games[$key])) {
            throw new ScoreboardException('Game not found.');
        }

        unset($this->games[$key]);
    }

    public function updateScore(string $homeTeam, string $awayTeam, int $homeScore, int $awayScore): void
    {
        if ($homeScore < 0 || $awayScore < 0) {
            throw new ScoreboardException('Scores must not be negative.');
        }

        $key = $this->key($homeTeam, $awayTeam);
        if (!isset($this->games[$key])) {
            throw new ScoreboardException('Game not found.');
        }

        $this->games[$key]->updateScore($homeScore, $awayScore);
    }

    /**
     * @return list<Game>
     */
    public function getSummary(): array
    {
        $games = array_values($this->games);

        usort($games, static function (Game $a, Game $b): int {
            // Total score desc
            $totalCompare = $b->totalScore() <=> $a->totalScore();
            if ($totalCompare !== 0) {
                return $totalCompare;
            }

            // Most recently added first (higher startedOrder first)
            return $b->startedOrder <=> $a->startedOrder;
        });

        return $games;
    }

    private function key(string $homeTeam, string $awayTeam): string
    {
        // case-insensitive key (so "Mexico" and "mexico" map to same game)
        return mb_strtolower(trim($homeTeam)) . '::' . mb_strtolower(trim($awayTeam));
    }
}
