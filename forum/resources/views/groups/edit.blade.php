@extends('layouts.app')

@section('title', 'Edit Group - Community Forum')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">✏️ Edit Group: {{ $group->name }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('groups.update', $group->slug) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $group->name) }}" required minlength="3" maxlength="100">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" maxlength="1000">{{ old('description', $group->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Group Type <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_public" value="public"
                                       {{ old('type', $group->type) === 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_public">🌐 Public</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type_private" value="private"
                                       {{ old('type', $group->type) === 'private' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_private">🔒 Private</label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Group Avatar</label>
                        @if ($group->avatar)
                            <div class="mb-2">
                                <img src="{{ $group->getAvatarUrl() }}" alt="Current avatar" class="rounded" width="64" height="64" style="object-fit: cover;">
                                <small class="text-muted ms-2">Current avatar</small>
                            </div>
                        @endif
                        <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Leave empty to keep current avatar. Max 2MB.</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
