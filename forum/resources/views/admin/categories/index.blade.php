@extends('layouts.app')

@section('title', 'Admin - Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categories &amp; Boards</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Add Category</button>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@forelse ($categories as $category)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>{{ $category->name }}</strong>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#editCategoryModal{{ $category->id }}">Edit</button>
                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}"
                    onsubmit="return confirm('Delete this category?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if ($category->description)
                <p class="text-muted small mb-3">{{ $category->description }}</p>
            @endif

            <table class="table table-sm mb-2">
                <thead>
                    <tr>
                        <th>Board</th>
                        <th>Description</th>
                        <th>Sort</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->boards as $board)
                        <tr>
                            <td>{{ $board->name }}</td>
                            <td class="text-muted small">{{ $board->description }}</td>
                            <td>{{ $board->sort_order }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBoardModal{{ $board->id }}">Edit</button>
                                    <form method="POST" action="{{ route('admin.boards.destroy', $board->id) }}"
                                        onsubmit="return confirm('Delete this board?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Board Modal --}}
                        <div class="modal fade" id="editBoardModal{{ $board->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.boards.update', $board->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Board</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $board->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $board->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control" value="{{ $board->sort_order }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center">No boards yet.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <button class="btn btn-sm btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#addBoardModal{{ $category->id }}">+ Add Board</button>
        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $category->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $category->sort_order }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Board Modal --}}
    <div class="modal fade" id="addBoardModal{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.categories.boards.store', $category->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Board to "{{ $category->name }}"</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Board</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">No categories yet.</div>
@endforelse

<div class="mt-4">{{ $categories->links() }}</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
