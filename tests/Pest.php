<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


uses(TestCase::class)->in('Feature', 'Unit');
uses(RefreshDatabase::class)->in('Feature');
require_once __DIR__ . '/Support/helpers.php';

function loginAsRole(string $role): User
{
    /** @var \Tests\TestCase $test */
    $test = test();

    $user = $test->createUserWithRole($role);
    $test->actingAs($user);

    return $user;
}
