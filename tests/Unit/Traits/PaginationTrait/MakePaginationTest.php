<?php

declare(strict_types=1);

use App\Traits\PaginationTrait;
use Illuminate\Pagination\LengthAwarePaginator;

uses()->group('traits', 'pagination');

/** tiny invoker using the trait */
function paginationInvoker()
{
    return new class {
        use PaginationTrait;
    };
}

test('MakePagination', function () {
    $svc = paginationInvoker();

    // dataset
    $all = range(1, 50);
    $perPage = 10;
    $total = count($all);

    // --- page 1 (first) ---
    $page1Items = array_slice($all, 0, $perPage);
    $p1 = new LengthAwarePaginator($page1Items, $total, $perPage, 1, ['path' => '/test']);
    $res1 = $svc->makePagination($p1);

    expect($res1)->toHaveKeys(['pagination', 'items']);
    expect($res1['pagination'])->toMatchArray([
        'current_page' => 1,
        'last_page'    => 5,
        'next_page'    => 2,
        'prev_page'    => null,
        'per_page'     => 10,
        'total'        => 50,
    ]);
    expect($res1['items'])->toEqual($page1Items);

    // --- page 3 (middle) ---
    $page3Items = array_slice($all, 20, $perPage); // 21..30
    $p3 = new LengthAwarePaginator($page3Items, $total, $perPage, 3, ['path' => '/test']);
    $res3 = $svc->makePagination($p3);

    expect($res3['pagination'])->toMatchArray([
        'current_page' => 3,
        'last_page'    => 5,
        'next_page'    => 4,
        'prev_page'    => 2,
        'per_page'     => 10,
        'total'        => 50,
    ]);
    expect($res3['items'])->toEqual($page3Items);

    // --- page 5 (last) ---
    $page5Items = array_slice($all, 40, $perPage); // 41..50
    $p5 = new LengthAwarePaginator($page5Items, $total, $perPage, 5, ['path' => '/test']);
    $res5 = $svc->makePagination($p5);

    expect($res5['pagination'])->toMatchArray([
        'current_page' => 5,
        'last_page'    => 5,
        'next_page'    => null,
        'prev_page'    => 4,
        'per_page'     => 10,
        'total'        => 50,
    ]);
    expect($res5['items'])->toEqual($page5Items);
});
