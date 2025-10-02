<?php

use App\Services\FolderService;

it('replaces the last segment of a path with the provided name', function () {
    $updated = FolderService::createNewPath('/library/articles', 'Press Releases');

    expect($updated)->toBe('/library/Press Releases');
});
