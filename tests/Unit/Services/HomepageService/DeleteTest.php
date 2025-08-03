
<?php

use App\Models\Homepage;
use App\Services\HomepageService;

it('assert 200 homepageService->delete', function () {

    $homepageService = new HomepageService();
    $homepage =  $homepageService->create();
    $response = $homepageService->delete($homepage->id);

    expect($response)->toBeNull();

    $this->assertDatabaseMissing('homepages', [
        'id' => $homepage->id,
    ]);
});
