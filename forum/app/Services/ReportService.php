<?php

namespace App\Services;

use App\DTOs\ReportDTO;
use App\Models\Report;
use App\Models\User;
use App\Repositories\ReportRepository;
use RuntimeException;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository,
    ) {}

    public function createReport(User $user, ReportDTO $dto): Report
    {
        $alreadyReported = Report::where('user_id', $user->id)
            ->where('reportable_type', $dto->reportableType)
            ->where('reportable_id', $dto->reportableId)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyReported) {
            throw new RuntimeException('You have already reported this content.');
        }

        return $this->reportRepository->create($user->id, $dto);
    }

    public function resolve(int $reportId, User $moderator, ?string $reason = null): Report
    {
        $report = $this->reportRepository->findById($reportId);

        if (!$report) {
            throw new RuntimeException('Report not found.');
        }

        $this->reportRepository->resolve($reportId, $moderator->id);

        return $report->fresh();
    }

    public function dismiss(int $reportId, User $moderator, ?string $reason = null): Report
    {
        $report = $this->reportRepository->findById($reportId);

        if (!$report) {
            throw new RuntimeException('Report not found.');
        }

        $this->reportRepository->dismiss($reportId, $moderator->id);

        return $report->fresh();
    }
}
