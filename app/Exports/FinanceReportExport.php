<?php

namespace App\Exports;

use App\Models\OrderService;
use App\Models\Sale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceReportExport implements FromCollection, WithHeadings
{
    public function __construct(private int $companyId) {}

    public function collection(): Collection
    {
        $sales = Sale::where('company_id', $this->companyId)->sum('total');
        $orders = OrderService::where('company_id', $this->companyId)->sum('total');

        return collect([
            ['Sales', $sales],
            ['Order Services', $orders],
        ]);
    }

    public function headings(): array
    {
        return ['Tipo', 'Total'];
    }
}
