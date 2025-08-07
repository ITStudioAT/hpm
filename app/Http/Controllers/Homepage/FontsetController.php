<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Services\FonttypeService;

class FontsetController extends Controller
{
    public function __construct(private FonttypeService $svc) {}

    public function serve(string $fontset): Response
    {
        // Reuse the already-built logic in the service (ETag, Last-Modified, etc.)
        return $this->svc->serve($fontset);
    }
}
