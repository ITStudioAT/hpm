<?php

use App\Services\FolderService;

it('builds normalized folder paths with a leading slash', function () {
    $result = FolderService::createPath('/Blog//', 'Drafts & Updates');

    expect($result)->toBe('/Blog/Drafts-Updates');

    $root = FolderService::createPath('', '');
    expect($root)->toBe('/');
});
