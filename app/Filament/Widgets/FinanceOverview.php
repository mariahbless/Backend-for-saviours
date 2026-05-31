<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;

class FinanceOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalLoansDisbursed = Loan::where('status', 'approved')->sum('amount');
        $totalRepaid = Repayment::sum('amount');
        
        $pendingApprovals = Loan::where('status', 'pending')->count();
        $totalOutstanding = $totalLoansDisbursed - $totalRepaid;

        return [
            Stat::make('Total Loans Disbursed', number_format($totalLoansDisbursed))
                ->description('Total cash given out')
                ->color('success'),
            
            Stat::make('Total Repaid', number_format($totalRepaid))
                ->description('Net earnings from payments')
                ->color('success'),
                
            Stat::make('Total Outstanding', number_format($totalOutstanding))
                ->description('Unpaid balances')
                ->color('danger'),
                
            Stat::make('Pending Loan Approvals', $pendingApprovals)
                ->description('Applications awaiting review')
                ->color('warning'),
        ];
    }
}
