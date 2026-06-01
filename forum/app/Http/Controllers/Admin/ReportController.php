<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private ReportRepository $reportRepository,
        private ReportService $reportService,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $reports = $this->reportRepository->getPaginatedByStatus($status);

        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function resolve(Request $request, int $id): RedirectResponse
    {
        $reason = $request->input('reason');

        try {
            $this->reportService->resolve($id, auth()->user(), $reason);
            return redirect()->route('admin.reports.index')
                ->with('success', 'Report resolved successfully.');
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.reports.index')
                ->with('error', $e->getMessage());
        }
    }

    public function dismiss(Request $request, int $id): RedirectResponse
    {
        $reason = $request->input('reason');

        try {
            $this->reportService->dismiss($id, auth()->user(), $reason);
            return redirect()->route('admin.reports.index')
                ->with('success', 'Report dismissed.');
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.reports.index')
                ->with('error', $e->getMessage());
        }
    }
}
