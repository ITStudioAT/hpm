<?php

use App\Services\FolderService;

it('deduplicates and sorts folders case-insensitively', function () {
    $sorted = FolderService::sortFolders([
        '/blog',
        '/docs',
        '/blog',
        '/about',
        '/about',
    ]);

    expect($sorted)->toBe(['/about', '/blog', '/docs']);
    expect(array_values($sorted))->toBe($sorted);
});
