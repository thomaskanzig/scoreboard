<?php

declare(strict_types=1);

namespace App\Scoreboard\Model;

final class Team
{
    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromName(string $name): self
    {
        $name = trim($name);

        if ($name === '') {
            throw new \InvalidArgumentException('Team name must not be empty.');
        }

        return new self($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Used for case-insensitive lookups/keys.
     */
    public function normalized(): string
    {
        return mb_strtolower($this->name);
    }
}
