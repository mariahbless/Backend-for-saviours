<x-filament-panels::page>

    {{-- ===== WELCOME BANNER ===== --}}
    <div style="background: linear-gradient(135deg, #1E40AF, #3B82F6); border-radius: 16px; padding: 28px 32px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: white; margin: 0;">
                Welcome back, {{ auth()->user()->name }} 👋
            </h1>
            <p style="font-size: 14px; color: #BFDBFE; margin: 6px 0 0;">
                Here's what's happening with Saviours today — {{ now()->format('l, F j, Y') }}
            </p>
        </div>
        <div style="font-size: 48px;">🏦</div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 0px;">

        <div style="background: white; border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #3B82F6; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="background: #EFF6FF; padding: 12px; border-radius: 12px; font-size: 24px;">📋</div>
            <div>
                <p style="font-size: 13px; color: #6B7280; margin: 0;">Total Loans</p>
                <p style="font-size: 28px; font-weight: 700; color: #3B82F6; margin: 0;">{{ $stats['total_loans'] }}</p>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #22C55E; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="background: #F0FDF4; padding: 12px; border-radius: 12px; font-size: 24px;">💰</div>
            <div>
                <p style="font-size: 13px; color: #6B7280; margin: 0;">Total Disbursed</p>
                <p style="font-size: 20px; font-weight: 700; color: #22C55E; margin: 0;">UGX {{ number_format($stats['total_disbursed']) }}</p>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #FBBF24; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="background: #FFFBEB; padding: 12px; border-radius: 12px; font-size: 24px;">⏳</div>
            <div>
                <p style="font-size: 13px; color: #6B7280; margin: 0;">Pending Loans</p>
                <p style="font-size: 28px; font-weight: 700; color: #FBBF24; margin: 0;">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #EF4444; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="background: #FEF2F2; padding: 12px; border-radius: 12px; font-size: 24px;">❌</div>
            <div>
                <p style="font-size: 13px; color: #6B7280; margin: 0;">Rejected Loans</p>
                <p style="font-size: 28px; font-weight: 700; color: #EF4444; margin: 0;">{{ $stats['rejected'] }}</p>
            </div>
        </div>

    </div>

    {{-- ===== PIE CHART + MONTHLY TABLE ===== --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

        {{-- PIE CHART --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #EEF2FF; padding: 8px; border-radius: 8px; font-size: 18px;">🥧</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Loans by Status</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Distribution of all loan statuses</p>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <div style="width: 280px; height: 280px;">
                    <canvas id="loanStatusChart"></canvas>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 24px; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #FBBF24;"></div>
                    <span style="font-size: 13px; color: #6B7280;">Pending ({{ $statusData['pending'] }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #22C55E;"></div>
                    <span style="font-size: 13px; color: #6B7280;">Approved ({{ $statusData['approved'] }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: #EF4444;"></div>
                    <span style="font-size: 13px; color: #6B7280;">Rejected ({{ $statusData['rejected'] }})</span>
                </div>
            </div>
        </div>

        {{-- MONTHLY TABLE --}}
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="background: #EFF6FF; padding: 8px; border-radius: 8px; font-size: 18px;">📅</div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;">Monthly Loans ({{ now()->year }})</h2>
                    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Loan activity per month this year</p>
                </div>
            </div>
            @if(count($monthlyData) > 0)
                <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #F9FAFB; color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 12px 16px; text-align: left;">Month</th>
                            <th style="padding: 12px 16px; text-align: left;">No. of Loans</th>
                            <th style="padding: 12px 16px; text-align: left;">Amount (UGX)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyData as $row)
                            <tr style="border-top: 1px solid #F3F4F6;">
                                <td style="padding: 12px 16px; font-weight: 600; color: #374151;">{{ $row['month'] }}</td>
                                <td style="padding: 12px 16px; color: #6B7280;">{{ $row['total'] }}</td>
                                <td style="padding: 12px 16px; color: #6B7280;">{{ number_format($row['amount']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="display: flex; flex-direction: column; align-items: center; padding: 48px 0; color: #9CA3AF;">
                    <span style="font-size: 40px; margin-bottom: 12px;">📭</span>
                    <p style="font-size: 13px;">No loan data available for this year.</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('loanStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $statusData['pending'] }},
                            {{ $statusData['approved'] }},
                            {{ $statusData['rejected'] }}
                        ],
                        backgroundColor: ['#FBBF24', '#22C55E', '#EF4444'],
                        borderColor: ['#fff', '#fff', '#fff'],
                        borderWidth: 3,
                        hoverOffset: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percent = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                    return ` ${context.label}: ${context.raw} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</x-filament-panels::page>