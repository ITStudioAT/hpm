<?php

use App\Services\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    ensurePermissionTables();
});

if (! class_exists(UpdateServiceTestCommand::class)) {
    class UpdateServiceTestCommand
    {
        public array $lines = [];

        public function line(string $message): void
        {
            $this->lines[] = $message;
        }
    }
}

it('creates required roles and assigns super admin to the first user', function () {
    $user = makeUser(['email' => 'first@example.com']);

    $service = new UpdateService();
    $command = new UpdateServiceTestCommand();

    $service->initialize($command);

    expect(Role::whereIn('name', ['super_admin', 'admin', 'user'])->count())->toBe(3);
    expect($user->fresh()->hasRole('super_admin'))->toBeTrue();
    expect(collect($command->lines)->filter(fn ($line) => str_contains($line, 'Roles created')))->not->toBeEmpty();
    expect(collect($command->lines)->filter(fn ($line) => str_contains($line, 'First user is super_admin')))->not->toBeEmpty();
});
