@extends('layouts.app')

@section('title', 'Create Group - Community Forum')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">➕ Create New Group</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required minlength="3" maxlength="100">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" maxlength="1000">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Group Type <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_public" value="public"
                                       {{ old('type', 'public') === 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_public">🌐 Public - Anyone can join</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_private" value="private"
                                       {{ old('type') === 'private' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_private">🔒 Private - Invitation only</label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Group Avatar</label>
                        <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text">Max 2MB. Supported: JPG, PNG, GIF, WebP</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
