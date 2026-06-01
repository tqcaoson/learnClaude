@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">📂 Categories</h1>

            @forelse($categories as $category)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">{{ $category->name }}</h4>
                        @if($category->description)
                            <small class="text-muted">{{ $category->description }}</small>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @forelse($category->boards as $board)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="flex-grow-1">
                                    <a href="{{ route('boards.show', $board->slug) }}" class="fw-bold text-decoration-none fs-6">
                                        📋 {{ $board->name }}
                                    </a>
                                    @if($board->description)
                                        <p class="mb-0 text-muted small">{{ $board->description }}</p>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('boards.show', $board->slug) }}" class="btn btn-sm btn-outline-primary">
                                        View Board →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-muted">No boards in this category yet.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No categories found.</div>
            @endforelse
        </div>
    </div>
@endsection
