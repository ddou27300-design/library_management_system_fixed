@extends('layouts.app')
@section('title', __('menu.reports'))
@section('page-title', __('menu.library_reports'))

@push('styles')
<style>
.report-nav { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:24px; }
.report-nav-card {
    flex:1; min-width:160px; display:flex; flex-direction:column; align-items:center;
    gap:8px; padding:20px 16px; border-radius:var(--radius); text-decoration:none;
    background:var(--surface); border:2px solid var(--border);
    color:var(--text); transition:all var(--transition); cursor:pointer;
}
.report-nav-card:hover { border-color:var(--primary); transform:translateY(-2px); box-shadow:var(--shadow-md); }
.report-nav-card i { font-size:1.6rem; }
.report-nav-card span { font-size:.85rem; font-weight:600; text-align:center; }
.summary-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:16px; margin-bottom:28px; }
.sum-card { background:var(--surface); border-radius:var(--radius); padding:20px 18px; border:1px solid var(--border); }
.sum-card .sum-val { font-size:1.8rem; font-weight:700; margin:4px 0 2px; }
.sum-card .sum-label { font-size:.78rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
.sum-card .sum-sub { font-size:.78rem; color:var(--text-muted); }
.charts-row { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:24px; }
@media(max-width:768px){ .charts-row{ grid-template-columns:1fr; } }
.chart-card { background:var(--surface); border-radius:var(--radius); padding:20px; border:1px solid var(--border); }
.chart-card h4 { margin:0 0 16px; font-size:1rem; font-weight:600; color:var(--text); display:flex; align-items:center; gap:8px; }
.cat-table { width:100%; border-collapse:collapse; font-size:.88rem; }
.cat-table th { text-align:left; padding:8px 10px; border-bottom:2px solid var(--border); font-weight:600; color:var(--text-muted); font-size:.75rem; text-transform:uppercase; }
.cat-table td { padding:9px 10px; border-bottom:1px solid var(--border); }
.cat-table tr:last-child td { border-bottom:none; }
.bar-mini { height:6px; border-radius:3px; background:var(--primary); opacity:.7; margin-top:4px; }
</style>
@endpush

@section('content')

{{-- Quick Navigation --}}
<div class="report-nav">
    <a href="{{ route('reports.index') }}" class="report-nav-card" style="border-color:var(--primary,#1a3c5e);background:var(--primary,#1a3c5e);color:#fff;">
        <i class="fas fa-chart-bar"></i>
        <span >{{ __('menu.overview') }}</span>
    </a>
    <a href="{{ route('reports.overdue') }}" class="report-nav-card">
        <i class="fas fa-exclamation-triangle text-danger"></i>
        <span >{{ __('menu.overdue_books_title') }}</span>
    </a>
    <a href="{{ route('reports.fines') }}" class="report-nav-card">
        <i class="fas fa-dollar-sign text-warning"></i>
        <span >{{ __('menu.fines_title') }}</span>
    </a>
    <a href="{{ route('reports.popular') }}" class="report-nav-card">
        <i class="fas fa-fire" style="color:#ea580c"></i>
        <span >{{ __('menu.popular_title') }}</span>
    </a>
</div>

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.total_borrows') }}</div>
        <div class="sum-val text-primary">{{ number_format($summary['total_borrows']) }}</div>
        <div class="sum-sub" >{{ __('menu.all_time') }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.active_borrows_lbl') }}</div>
        <div class="sum-val text-success">{{ number_format($summary['active_borrows']) }}</div>
        <div class="sum-sub" >{{ __('menu.currently_out2') }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.overdue_lbl') }}</div>
        <div class="sum-val text-danger">{{ number_format($summary['overdue_count']) }}</div>
        <div class="sum-sub" >{{ __('menu.needs_attention2') }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.total_fines') }}</div>
        <div class="sum-val text-warning">${{ number_format($summary['total_fines'], 2) }}</div>
        <div class="sum-sub"><span >{{ __('menu.collected') }}</span>: ${{ number_format($summary['fines_collected'], 2) }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.total_books_lbl') }}</div>
        <div class="sum-val text-primary">{{ number_format($summary['total_books']) }}</div>
        <div class="sum-sub" >{{ __('menu.in_catalog') }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label" >{{ __('menu.students_lbl') }}</div>
        <div class="sum-val text-info">{{ number_format($summary['total_students']) }}</div>
        <div class="sum-sub" >{{ __('menu.registered') }}</div>
    </div>
</div>

{{-- Charts Row --}}
<div class="charts-row">
    {{-- Monthly Chart --}}
    <div class="chart-card">
        <h4>
            <i class="fas fa-chart-line text-primary"></i> 
            <span >{{ __('menu.monthly_activity') }}</span> ({{ now()->year }})
        </h4>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    {{-- Category Stats --}}
    <div class="chart-card table-wrap">
        <h4><i class="fas fa-tags text-primary"></i> <span >{{ __('menu.borrows_by_category') }}</span></h4>
        @php $maxBorrows = $categoryStats->max('borrows') ?: 1; @endphp
        <table class="cat-table">
            <thead>
                <tr>
                    <th >{{ __('menu.category') }}</th>
                    <th style="text-align:right" >{{ __('menu.books') }}</th>
                    <th style="text-align:right" >{{ __('menu.times_borrowed') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categoryStats->sortByDesc('borrows')->take(10) as $cat)
                <tr>
                    <td>
                        {{ $cat['name'] }}
                        <div class="bar-mini" style="width:{{ ($cat['borrows']/$maxBorrows)*100 }}%"></div>
                    </td>
                    <td style="text-align:right">{{ $cat['books'] }}</td>
                    <td style="text-align:right;font-weight:600">{{ $cat['borrows'] }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted" style="padding:20px" >{{ __('menu.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Monthly Table --}}
<div class="chart-card">
    <h4><i class="fas fa-table text-success"></i> <span >{{ __('menu.monthly_summary') }}</span> — {{ now()->year }}</h4>
    <div style="overflow-x:auto">
        <table class="cat-table">
            <thead>
                <tr>
                    <th >{{ __('menu.month') }}</th>
                    <th style="text-align:right" >{{ __('menu.status_borrowed') }}</th>
                    <th style="text-align:right" >{{ __('menu.status_returned') }}</th>
                    <th style="text-align:right" >{{ __('menu.collected') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlySummary as $row)
                <tr>
                    <td>{{ $row['month'] }}</td>
                    <td style="text-align:right">{{ $row['borrowed'] }}</td>
                    <td style="text-align:right">{{ $row['returned'] }}</td>
                    <td style="text-align:right">{{ $row['fines'] > 0 ? '$'.number_format($row['fines'],2) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($monthlySummary->pluck('month')),
        datasets: [
            {
                label: '{{ __('menu.status_borrowed') }}',
                data: @json($monthlySummary->pluck('borrowed')),
                backgroundColor: 'rgba(26,60,94,.75)',
                borderRadius: 4,
            },
            {
                label: '{{ __('menu.status_returned') }}',
                data: @json($monthlySummary->pluck('returned')),
                backgroundColor: 'rgba(27,122,78,.65)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endpush