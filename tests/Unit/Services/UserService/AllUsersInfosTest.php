<?php

declare(strict_types=1);

use App\Services\UserService;

uses()->group('userservice', 'allUsersInfos');

test('AllUsersInfos', function () {
    $data = (new UserService())->allUsersInfos();

    // Shape and titles are stable; counts depend on your fixtures
    expect($data)->toBeArray()->toHaveCount(6);

    $titles = array_column($data, 'title');
    expect($titles)->toMatchArray([
        'Gesamt',
        'Aktiv',
        'Mit 2-FA-Authentifizierung',
        'Mit bestätigter E-Mail',
        'Bestätigte',
        'Nicht bestätigte',
    ]);

    foreach ($data as $row) {
        expect($row)->toHaveKeys(['title', 'content']);
        expect(is_int($row['content']))->toBeTrue();
    }
});
