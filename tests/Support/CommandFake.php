<?php

namespace Tests\Support;

class CommandFake
{
    public array $lines = [];

    public function line(string $message): void
    {
        $this->lines[] = $message;
    }

    public function last(): ?string
    {
        return $this->lines ? end($this->lines) : null;
    }
}
