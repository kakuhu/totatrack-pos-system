<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? Carbon::today()->toDateString();
        $to = $request->to ?? Carbon::today()->toDateString();

        $query = Sale::with('items.product');

        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }

        $query->whereDate('sold_at', '>=', $from)
              ->whereDate('sold_at', '<=', $to);

        $totalSales = (clone $query)->sum('total');

        $sales = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('sales.report', compact(
            'sales',
            'from',
            'to',
            'totalSales'
        ));
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from ?? Carbon::today()->toDateString();
        $to = $request->to ?? Carbon::today()->toDateString();

        $query = Sale::with('items.product');

        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }

        $query->whereDate('sold_at', '>=', $from)
              ->whereDate('sold_at', '<=', $to);

        $sales = $query->latest()->get();
        $totalSales = $sales->sum('total');

        $pdf = Pdf::loadView('sales.report_pdf', [
            'sales' => $sales,
            'from' => $from,
            'to' => $to,
            'totalSales' => $totalSales,
        ]);

        return $pdf->download("sales-report-{$from}-to-{$to}.pdf");
    }
}
