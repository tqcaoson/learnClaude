@extends('layouts.admin')

@section('title', 'Admin Statistics')
@section('page-title', 'Statistics')
@section('breadcrumb')
    <li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Forum Statistics</h2>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text text-white-50 mb-1">Total Users</p>
                            <h3 class="card-title mb-0">{{ number_format($stats['total_users']) }}</h3>
                            <small class="text-white-75">+{{ $stats['new_users_week'] }} this week</small>
                        </div>
                        <div class="fs-1 opacity-50">👥</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text text-white-50 mb-1">Total Posts</p>
                            <h3 class="card-title mb-0">{{ number_format($stats['total_posts']) }}</h3>
                            <small class="text-white-75">
                                {{ $stats['published_posts'] }} published &middot; {{ $stats['draft_posts'] }} draft
                            </small>
                        </div>
                        <div class="fs-1 opacity-50">📝</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text text-white-50 mb-1">Total Comments</p>
                            <h3 class="card-title mb-0">{{ number_format($stats['total_comments']) }}</h3>
                            <small class="text-white-75">Across all posts</small>
                        </div>
                        <div class="fs-1 opacity-50">💬</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text text-white-50 mb-1">Pending Reports</p>
                            <h3 class="card-title mb-0">{{ number_format($stats['pending_reports']) }}</h3>
                            <small class="text-white-75">Requires action</small>
                        </div>
                        <div class="fs-1 opacity-50">🚩</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Groups</p>
                    <h4 class="mb-0">{{ number_format($stats['total_groups']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Reactions</p>
                    <h4 class="mb-0">{{ number_format($stats['total_reactions']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">New Users Today</p>
                    <h4 class="mb-0">{{ number_format($stats['new_users_today']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Published Posts</p>
                    <h4 class="mb-0">{{ number_format($stats['published_posts']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Posts &amp; Users (Last 30 days)</div>
                <div class="card-body">
                    <canvas id="activityChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Post Status Distribution</div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="postStatusChart" style="max-height:260px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Tags + Recent Activity --}}
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Top Tags</div>
                <div class="card-body">
                    @if($topTags->count() > 0)
                        <canvas id="tagsChart" height="200"></canvas>
                    @else
                        <p class="text-muted text-center py-3">No tags data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Recent Users</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-muted small">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $user->role ?? 'user' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No users yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Recent Posts</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPosts as $post)
                                <tr>
                                    <td>
                                        <a href="{{ route('posts.show', $post->slug ?? $post->id) }}" class="text-decoration-none text-truncate d-inline-block" style="max-width:180px;">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">{{ $post->user->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $post->status }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $post->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No posts yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const postsData = @json($postsPerDay);
const usersData = @json($usersPerDay);
const tagsData  = @json($topTags);

// Generate last 30 days labels
const labels = [];
const labelDates = [];
for (let i = 29; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    labels.push(d.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' }));
    labelDates.push(d.toISOString().slice(0, 10)); // YYYY-MM-DD
}

function mapDataToLabels(data, dates) {
    return dates.map(date => {
        const match = data.find(d => d.date === date);
        return match ? parseInt(match.count) : 0;
    });
}

// Line chart: Posts & Users activity
new Chart(document.getElementById('activityChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Posts',
                data: mapDataToLabels(postsData, labelDates),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
            },
            {
                label: 'New Users',
                data: mapDataToLabels(usersData, labelDates),
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
        }
    }
});

// Doughnut: Post status distribution
new Chart(document.getElementById('postStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Published', 'Draft'],
        datasets: [{
            data: [{{ $stats['published_posts'] }}, {{ $stats['draft_posts'] }}],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)'
            ],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Horizontal bar: Top tags
@if($topTags->count() > 0)
new Chart(document.getElementById('tagsChart'), {
    type: 'bar',
    data: {
        labels: tagsData.map(t => t.name),
        datasets: [{
            label: 'Posts',
            data: tagsData.map(t => t.posts_count),
            backgroundColor: 'rgba(102, 126, 234, 0.7)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 1,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
        }
    }
});
@endif
</script>
@endsection
@endsection
