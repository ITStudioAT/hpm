<?php

uses()->beforeEach(function () {
    // no-op
})->afterEach(function () {
    if (class_exists(\Mockery::class)) {
        \Mockery::close();
    }
});
