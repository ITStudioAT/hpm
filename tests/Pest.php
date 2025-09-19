<?php

use Tests\TestCase;
use App\Models\User;

uses(TestCase::class)->in('Feature', 'Unit');

function loginAsRole(string $role): User
{
    /** @var \Tests\TestCase $test */
    $test = test();

    $user = $test->createUserWithRole($role);
    $test->actingAs($user);

    return $user;
}
