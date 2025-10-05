<?php

use App\Services\FolderService;

it('normalizes arbitrary structures to the configured folder schema', function () {
    $normalized = FolderService::normalizeStructure([
        'folders' => ['/', '/Blog', '/Blog/Child'],
        'unexpected' => 'drop-me',
    ]);

    expect($normalized)->toHaveKey('folders');
    expect($normalized['folders'])->toBe(['/']);

    $fallback = FolderService::normalizeStructure(['folders' => 'not-an-array']);
    expect($fallback['folders'])->toBe(['/']);
});
