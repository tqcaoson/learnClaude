<?php

namespace App\Actions\Report;

use App\DTOs\ReportDTO;
use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;

class CreateReportAction
{
    public function __construct(
        private ReportService $reportService,
    ) {}

    public function execute(User $user, ReportDTO $dto): Report
    {
        return $this->reportService->createReport($user, $dto);
    }
}
