<?php

namespace App\Repositories;

use App\DTOs\ReportDTO;
use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportRepository
{
    public function create(int $userId, ReportDTO $dto): Report
    {
        return Report::create([
            'user_id' => $userId,
            'reportable_type' => $dto->reportableType,
            'reportable_id' => $dto->reportableId,
            'reason' => $dto->reason,
            'description' => $dto->description,
            'status' => 'pending',
        ]);
    }

    public function getPaginatedByStatus(string $status = 'pending', int $perPage = 20): LengthAwarePaginator
    {
        return Report::where('status', $status)
            ->with(['reporter', 'resolver'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Report
    {
        return Report::find($id);
    }

    public function resolve(int $reportId, int $moderatorId): bool
    {
        return Report::where('id', $reportId)->update([
            'status' => 'resolved',
            'resolved_by' => $moderatorId,
            'resolved_at' => now(),
        ]) > 0;
    }

    public function dismiss(int $reportId, int $moderatorId): bool
    {
        return Report::where('id', $reportId)->update([
            'status' => 'dismissed',
            'resolved_by' => $moderatorId,
            'resolved_at' => now(),
        ]) > 0;
    }
}
