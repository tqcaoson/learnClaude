<?php

namespace App\Http\Controllers;

use App\Actions\Report\CreateReportAction;
use App\DTOs\ReportDTO;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    public function __construct(
        private CreateReportAction $createReportAction,
    ) {}

    public function store(ReportRequest $request): RedirectResponse
    {
        $dto = new ReportDTO(
            reportableType: $request->input('reportable_type'),
            reportableId: (int) $request->input('reportable_id'),
            reason: $request->input('reason'),
            description: $request->input('description'),
        );

        try {
            $this->createReportAction->execute(auth()->user(), $dto);
            return redirect()->back()->with('success', 'Report submitted successfully. Our moderators will review it.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
