
<?php

use App\Models\Homepage;
use App\Services\HomepageService;

it('assert 200 homepageService->create', function () {

    $homepageService = new HomepageService();
    $response = $homepageService->create();

    $homepage_id = $response->id;

    expect($homepage_id)->not->toBeNull();

    $this->assertDatabaseHas('homepages', [
        'id' => $homepage_id,
        'name' => $response->name,
        'path' => '',
        'type' => 'homepage',
        'structure' => null,
    ]);
});
