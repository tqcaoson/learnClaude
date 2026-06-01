<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService
    ) {}

    public function index(Request $request): View
    {
        $request->validate([
            'q'      => 'nullable|string|max:200',
            'tag'    => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
        ]);

        $query   = trim($request->input('q', ''));
        $filters = [
            'tag'    => $request->input('tag'),
            'author' => $request->input('author'),
        ];

        $results = null;
        $total   = 0;

        if ($query !== '') {
            $results = $this->searchService->search($query, $filters);
            $total   = $results->total();
        }

        return view('search.results', compact('results', 'query', 'filters', 'total'));
    }
}
