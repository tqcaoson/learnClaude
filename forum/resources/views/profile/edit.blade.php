@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="mb-4">✏️ Edit Profile</h2>

        {{-- Avatar Upload --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">📷 Profile Picture</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                    <div>
                        <p class="mb-0 text-muted small">Accepted formats: JPEG, PNG, WebP. Max size: 2MB.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" name="avatar" accept="image/jpeg,image/png,image/webp" required>
                        <button type="submit" class="btn btn-outline-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">👤 Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4" maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $user->location) }}" maxlength="255">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="website_url" class="form-label">Website URL</label>
                        <input type="url" class="form-control @error('website_url') is-invalid @enderror" id="website_url" name="website_url" value="{{ old('website_url', $user->website_url) }}" maxlength="255" placeholder="https://example.com">
                        @error('website_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                        <a href="{{ route('profile.show', ['id' => auth()->id()]) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
