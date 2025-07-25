<?php

namespace Core\Analytics;

use App\Http\Controllers\Controller;
use Core\Http\Response;

/**
 * Class AnalyticsController
 *
 * Handles analytics-related HTTP requests.
 */
class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;
    protected AnalyticsReport $analyticsReport;

    public function __construct(AnalyticsService $analyticsService, AnalyticsReport $analyticsReport)
    {
        $this->analyticsService = $analyticsService;
        $this->analyticsReport = $analyticsReport;
    }

    public function getReport()
    {
        return $this->analyticsService->getReport();
    }

    public function generateDashboardReport(): Response
    {
        $report = $this->analyticsReport->generate();
        return new Response(json_encode($report), 200);
    }
}

?>
