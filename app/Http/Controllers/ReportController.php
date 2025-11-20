<?php

namespace App\Http\Controllers;

use App\Exports\FinanceReportExport;
use App\Models\OrderService;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.basic', 'permission:manage finances']);
    }

    public function index(): View
    {
        $companyId = Auth::user()->company_id;
        $sales = Sale::where('company_id', $companyId)->sum('total');
        $orders = OrderService::where('company_id', $companyId)->sum('total');

        return view('finance.reports.index', [
            'sales_total' => $sales,
            'order_total' => $orders,
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new FinanceReportExport(Auth::user()->company_id), 'finance-report.xlsx');
    }

    public function exportPdf()
    {
        $companyId = Auth::user()->company_id;
        $sales = Sale::where('company_id', $companyId)->sum('total');
        $orders = OrderService::where('company_id', $companyId)->sum('total');

        $pdf = Pdf::loadView('finance.reports.pdf', [
            'sales_total' => $sales,
            'order_total' => $orders,
        ]);

        Log::info('PDF export generated');

        return $pdf->download('finance-report.pdf');
    }
}
