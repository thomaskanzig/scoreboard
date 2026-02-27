<?php

declare(strict_types=1);

namespace App\Tests\Scoreboard;

use App\Scoreboard\Exception\ScoreboardException;
use App\Scoreboard\Scoreboard;
use PHPUnit\Framework\TestCase;

final class ScoreboardTest extends TestCase
{
    public function testStartGameInitializesAtZeroZero(): void
    {
        $scoreboard = new Scoreboard();

        $scoreboard->startGame('Mexico', 'Canada');
        $summary = $scoreboard->getSummary();

        self::assertCount(1, $summary);
        self::assertSame('Mexico 0 - Canada 0', $summary[0]->toSummaryString());
    }

    public function testStartGameDuplicateIsRejected(): void
    {
        $scoreboard = new Scoreboard();
        $scoreboard->startGame('Mexico', 'Canada');

        $this->expectException(ScoreboardException::class);
        $this->expectExceptionMessage('This game is already in progress.');

        $scoreboard->startGame('Mexico', 'Canada');
    }

    public function testUpdateScoreWorks(): void
    {
        $scoreboard = new Scoreboard();
        $scoreboard->startGame('Mexico', 'Canada');

        $scoreboard->updateScore('Mexico', 'Canada', 0, 5);

        $summary = $scoreboard->getSummary();
        self::assertSame('Mexico 0 - Canada 5', $summary[0]->toSummaryString());
    }

    public function testFinishGameRemovesIt(): void
    {
        $scoreboard = new Scoreboard();
        $scoreboard->startGame('Mexico', 'Canada');

        $scoreboard->finishGame('Mexico', 'Canada');

        self::assertCount(0, $scoreboard->getSummary());
    }

    public function testSummarySortingByTotalScoreAndRecency(): void
    {
        $scoreboard = new Scoreboard();

        $scoreboard->startGame('Mexico', 'Canada');
        $scoreboard->startGame('Spain', 'Brazil');
        $scoreboard->startGame('Germany', 'France');
        $scoreboard->startGame('Uruguay', 'Italy');
        $scoreboard->startGame('Argentina', 'Australia');

        $scoreboard->updateScore('Mexico', 'Canada', 0, 5);
        $scoreboard->updateScore('Spain', 'Brazil', 10, 2);
        $scoreboard->updateScore('Germany', 'France', 2, 2);
        $scoreboard->updateScore('Uruguay', 'Italy', 6, 6);
        $scoreboard->updateScore('Argentina', 'Australia', 3, 1);

        $summary = array_map(function ($g) {
            return $g->toSummaryString();
        }, $scoreboard->getSummary());

        self::assertSame([
            'Uruguay 6 - Italy 6',
            'Spain 10 - Brazil 2',
            'Mexico 0 - Canada 5',
            'Argentina 3 - Australia 1',
            'Germany 2 - France 2',
        ], $summary);
    }

    public function testUpdateScoreOnMissingGameThrows(): void
    {
        $scoreboard = new Scoreboard();

        $this->expectException(ScoreboardException::class);
        $this->expectExceptionMessage('Game not found');

        $scoreboard->updateScore('Foo', 'Bar', 1, 1);
    }

    public function testStartGameWithSameTeamNameThrows(): void
    {
        $scoreboard = new Scoreboard();

        $this->expectException(ScoreboardException::class);
        $this->expectExceptionMessage('Home and away team must be different.');

        $scoreboard->startGame('Mexico', 'Mexico');
    }
}
