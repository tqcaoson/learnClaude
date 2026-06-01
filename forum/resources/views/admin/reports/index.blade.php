@extends('layouts.admin')

@section('title', 'Reports - Admin')
@section('page-title', 'Reports - Admin')

@section('content')
<h3 class="mb-4">🚨 Reports Management</h3>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'pending']) }}">
            ⏳ Pending
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'resolved' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'resolved']) }}">
            ✅ Resolved
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $status === 'dismissed' ? 'active' : '' }}" href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}">
            ❌ Dismissed
        </a>
    </li>
</ul>

@if ($reports->isEmpty())
    <p class="text-muted text-center py-4">No reports found.</p>
@else
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Reporter</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->reporter->name ?? 'Unknown' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($report->reportable_type) }} #{{ $report->reportable_id }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ ucfirst($report->reason) }}</span>
                                @if ($report->description)
                                    <br><small class="text-muted">{{ Str::limit($report->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($report->status === 'pending')
                                    <span class="badge bg-info">Pending</span>
                                @elseif ($report->status === 'resolved')
                                    <span class="badge bg-success">Resolved</span>
                                @else
                                    <span class="badge bg-secondary">Dismissed</span>
                                @endif
                            </td>
                            <td><small>{{ $report->created_at->format('M j, Y') }}</small></td>
                            <td>
                                @if ($report->isPending())
                                    <button type="button" class="btn btn-sm btn-success me-1"
                                            data-bs-toggle="modal" data-bs-target="#resolveModal{{ $report->id }}">
                                        Resolve
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#dismissModal{{ $report->id }}">
                                        Dismiss
                                    </button>

                                    <!-- Resolve Modal -->
                                    <div class="modal fade" id="resolveModal{{ $report->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.reports.resolve', $report->id) }}">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Resolve Report #{{ $report->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Reason (optional)</label>
                                                            <textarea name="reason" class="form-control" rows="3" placeholder="Notes for this resolution..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Resolve</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dismiss Modal -->
                                    <div class="modal fade" id="dismissModal{{ $report->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.reports.dismiss', $report->id) }}">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Dismiss Report #{{ $report->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Reason (optional)</label>
                                                            <textarea name="reason" class="form-control" rows="3" placeholder="Why is this report being dismissed?"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-outline-secondary">Dismiss</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <small class="text-muted">by {{ $report->resolver->name ?? 'Unknown' }}</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $reports->withQueryString()->links() }}
    </div>
@endif
@endsection
