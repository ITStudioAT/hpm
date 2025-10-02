<?php

use App\Services\FolderService;

it('replaces matching path prefixes inside the folders collection', function () {
    $folders = ['/library', '/library/2024', '/other'];

    $updated = FolderService::changePathInFolders($folders, '/library', '/archives');

    expect($updated)->toBe(['/archives', '/archives/2024', '/other']);
});
