@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Library Reports')

@push('styles')
<style>
.report-nav { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:24px; }
.report-nav-card {
    flex:1; min-width:160px; display:flex; flex-direction:column; align-items:center;
    gap:8px; padding:20px 16px; border-radius:12px; text-decoration:none;
    background:var(--card-bg,#fff); border:2px solid var(--border,#e5e9f2);
    color:var(--text,#1e2a3a); transition:all .2s; cursor:pointer;
}
.report-nav-card:hover { border-color:var(--primary,#1a3c5e); transform:translateY(-2px); box-shadow:0 4px 16px rgba(26,60,94,.12); }
.report-nav-card i { font-size:1.6rem; }
.report-nav-card span { font-size:.85rem; font-weight:600; text-align:center; }
.summary-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:16px; margin-bottom:28px; }
.sum-card { background:var(--card-bg,#fff); border-radius:12px; padding:20px 18px; border:1px solid var(--border,#e5e9f2); }
.sum-card .sum-val { font-size:1.8rem; font-weight:700; margin:4px 0 2px; }
.sum-card .sum-label { font-size:.78rem; color:var(--text-muted,#6b7794); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
.sum-card .sum-sub { font-size:.78rem; color:var(--text-muted,#6b7794); }
.charts-row { display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:24px; }
@media(max-width:768px){ .charts-row{ grid-template-columns:1fr; } }
.chart-card { background:var(--card-bg,#fff); border-radius:12px; padding:20px; border:1px solid var(--border,#e5e9f2); }
.chart-card h4 { margin:0 0 16px; font-size:1rem; font-weight:600; color:var(--text,#1e2a3a); display:flex; align-items:center; gap:8px; }
.cat-table { width:100%; border-collapse:collapse; font-size:.88rem; }
.cat-table th { text-align:left; padding:8px 10px; border-bottom:2px solid var(--border,#e5e9f2); font-weight:600; color:var(--text-muted,#6b7794); font-size:.75rem; text-transform:uppercase; }
.cat-table td { padding:9px 10px; border-bottom:1px solid var(--border,#e5e9f2); }
.cat-table tr:last-child td { border-bottom:none; }
.bar-mini { height:6px; border-radius:3px; background:var(--primary,#1a3c5e); opacity:.7; margin-top:4px; }
</style>
@endpush

@section('content')

{{-- Quick Navigation --}}
<div class="report-nav">
    <a href="{{ route('reports.index') }}" class="report-nav-card" style="border-color:var(--primary,#1a3c5e);background:var(--primary,#1a3c5e);color:#fff;">
        <i class="fas fa-chart-bar"></i>
        <span>Overview</span>
    </a>
    <a href="{{ route('reports.overdue') }}" class="report-nav-card">
        <i class="fas fa-exclamation-triangle" style="color:#dc2626"></i>
        <span>Overdue Books</span>
    </a>
    <a href="{{ route('reports.fines') }}" class="report-nav-card">
        <i class="fas fa-dollar-sign" style="color:#d97706"></i>
        <span>Fines</span>
    </a>
    <a href="{{ route('reports.popular') }}" class="report-nav-card">
        <i class="fas fa-fire" style="color:#ea580c"></i>
        <span>Popular Books</span>
    </a>
</div>

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="sum-card">
        <div class="sum-label">Total Borrows</div>
        <div class="sum-val" style="color:#2563eb">{{ number_format($summary['total_borrows']) }}</div>
        <div class="sum-sub">All time</div>
    </div>
    <div class="sum-card">
        <div class="sum-label">Active Borrows</div>
        <div class="sum-val" style="color:#16a34a">{{ number_format($summary['active_borrows']) }}</div>
        <div class="sum-sub">Currently out</div>
    </div>
    <div class="sum-card">
        <div class="sum-label">Overdue</div>
        <div class="sum-val" style="color:#dc2626">{{ number_format($summary['overdue_count']) }}</div>
        <div class="sum-sub">Needs attention</div>
    </div>
    <div class="sum-card">
        <div class="sum-label">Total Fines</div>
        <div class="sum-val" style="color:#d97706">${{ number_format($summary['total_fines'], 2) }}</div>
        <div class="sum-sub">Collected: ${{ number_format($summary['fines_collected'], 2) }}</div>
    </div>
    <div class="sum-card">
        <div class="sum-label">Total Books</div>
        <div class="sum-val" style="color:#7c3aed">{{ number_format($summary['total_books']) }}</div>
        <div class="sum-sub">In catalog</div>
    </div>
    <div class="sum-card">
        <div class="sum-label">Students</div>
        <div class="sum-val" style="color:#0891b2">{{ number_format($summary['total_students']) }}</div>
        <div class="sum-sub">Registered</div>
    </div>
</div>

{{-- Charts Row --}}
<div class="charts-row">
    {{-- Monthly Chart --}}
    <div class="chart-card">
        <h4><i class="fas fa-chart-line" style="color:#2563eb"></i> Monthly Borrow Activity ({{ now()->year }})</h4>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    {{-- Category Stats --}}
    <div class="chart-card">
        <h4><i class="fas fa-tags" style="color:#7c3aed"></i> Borrows by Category</h4>
        @php $maxBorrows = $categoryStats->max('borrows') ?: 1; @endphp
        <table class="cat-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th style="text-align:right">Books</th>
                    <th style="text-align:right">Borrows</th>
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
                <tr><td colspan="3" class="text-center text-muted" style="padding:20px">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Monthly Table --}}
<div class="chart-card">
    <h4><i class="fas fa-table" style="color:#16a34a"></i> Monthly Summary Table — {{ now()->year }}</h4>
    <div style="overflow-x:auto">
        <table class="cat-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th style="text-align:right">Borrowed</th>
                    <th style="text-align:right">Returned</th>
                    <th style="text-align:right">Fines Collected</th>
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
                label: 'Borrowed',
                data: @json($monthlySummary->pluck('borrowed')),
                backgroundColor: 'rgba(37,99,235,.75)',
                borderRadius: 4,
            },
            {
                label: 'Returned',
                data: @json($monthlySummary->pluck('returned')),
                backgroundColor: 'rgba(22,163,74,.65)',
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
