<?php

namespace Tests\Helpers;

class TestUserFake
{
    public int $id;
    public string $email;
    public ?string $email_2fa = null;
    public bool $is_2fa = False;
    public ?string $name = null;
    public $email_verified_at = null;
    public $confirmed_at = null;

    public function __construct(array $attrs = [])
    {
        foreach ($attrs as $k => $v) {
            $this->$k = $v;
        }
    }

    public function rememberLogin(): void
    {
        // noop for tests
    }

    public function setPassword(string $password): void
    {
        // noop for tests
    }

    public function save(): void
    {
        // noop for tests
    }
}
