<?php

use App\Services\FolderService;

it('detects whether a folder has descendants', function () {
    $folders = ['/library', '/library/2024', '/other'];

    expect(FolderService::hasSubFolder('/library', $folders))->toBeTrue();
    expect(FolderService::hasSubFolder('/other', $folders))->toBeFalse();
});
