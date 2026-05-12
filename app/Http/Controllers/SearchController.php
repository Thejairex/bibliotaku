<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Search', [
            'query' => request('q', ''),
            'mode' => request('mode', 'local'),
        ]);
    }
}
