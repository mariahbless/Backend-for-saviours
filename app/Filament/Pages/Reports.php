<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;
use UnitEnum;
use BackedEnum;

class Reports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static UnitEnum|string|null $navigationGroup = 'Reports & Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Reports';

    public array $stats = [];
    public array $monthlyData = [];
    public array $statusData = [];

    public function mount(): void
    {
        $this->stats = [
            'total_loans'     => Loan::count(),
            'total_disbursed' => Loan::where('status', 'approved')->sum('amount'),
            'pending'         => Loan::where('status', 'pending')->count(),
            'approved'        => Loan::where('status', 'approved')->count(),
            'rejected'        => Loan::where('status', 'rejected')->count(),
        ];

        $this->statusData = [
            'pending'  => Loan::where('status', 'pending')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'rejected' => Loan::where('status', 'rejected')->count(),
        ];

        $this->monthlyData = Loan::selectRaw('MONTH(created_at) as month, COUNT(*) as total, SUM(amount) as amount')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($row) => [
                'month'  => date('M', mktime(0, 0, 0, $row->month, 1)),
                'total'  => $row->total,
                'amount' => $row->amount,
            ])
            ->toArray();
    }
}