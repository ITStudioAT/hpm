<?php

use function Pest\Laravel\artisan;

it('displays the version from config', function () {
    config()->set('hpm.version', '9.9.9');

    artisan('hpm')
        ->expectsOutput('9.9.9')
        ->assertExitCode(0);
});
