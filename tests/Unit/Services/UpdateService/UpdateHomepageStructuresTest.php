<?php

use App\Models\Homepage;
use App\Services\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

it('normalizes stored homepage structures to match the current schema', function () {
    $homepage = Homepage::create([
        'name' => 'Main Homepage',
        'structure' => ['id' => 99],
    ]);

    $menu = Homepage::withoutGlobalScopes()->create([
        'name' => 'Primary Menu',
        'type' => 'menu',
        'structure' => json_encode(['content' => [['unused' => true]]]),
    ]);

    $service = new UpdateService();
    $command = new UpdateServiceTestCommand();

    $service->updateHomepageStructures($command);

    $expectedHomepageStructure = config('hpm.structures.homepage');
    $expectedHomepageStructure['index']['id'] = 99;

    expect($homepage->fresh()->structure)->toMatchArray($expectedHomepageStructure);
    expect($menu->fresh()->structure)->toMatchArray(config('hpm.structures.menu'));
    expect(collect($command->lines)->filter(fn ($line) => str_contains($line, 'structure normalized')))->not->toBeEmpty();
});
