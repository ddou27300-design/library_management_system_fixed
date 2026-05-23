@extends('layouts.app')
@section('title', __('menu.dashboard'))
@section('page-title', __('menu.dashboard'))

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div>
        <h2>{{ __('menu.welcome_back') }}, {{ Auth::user()->name }}! 👋</h2>
        <p>{{ __('menu.library_today') }}</p>
    </div>
    <i class="fas fa-book-open welcome-icon"></i>
</div>

{{-- Quick Actions --}}
<div class="quick-actions">
    <a href="{{ route('borrows.create') }}" class="quick-action-btn">
        <i class="fas fa-hand-holding-heart" style="color:#2563eb"></i>
        <span>{{ __('menu.issue_book') }}</span>
    </a>
    <a href="{{ route('books.create') }}" class="quick-action-btn">
        <i class="fas fa-plus-circle" style="color:#16a34a"></i>
        <span>{{ __('menu.add_book') }}</span>
    </a>
    <a href="{{ route('students.create') }}" class="quick-action-btn">
        <i class="fas fa-user-plus" style="color:#d97706"></i>
        <span>{{ __('menu.register_student') }}</span>
    </a>
    <a href="{{ route('reports.overdue') }}" class="quick-action-btn">
        <i class="fas fa-exclamation-triangle" style="color:#dc2626"></i>
        <span>{{ __('menu.overdue') }}</span>
    </a>
    <a href="{{ route('reports.index') }}" class="quick-action-btn">
        <i class="fas fa-chart-bar" style="color:#7c3aed"></i>
        <span>{{ __('menu.reports') }}</span>
    </a>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-book"></i></div>
        <div class="stat-info">
            <p class="stat-label">{{ __('menu.total_books') }}</p>
            <h2 class="stat-value">{{ number_format($stats['total_books']) }}</h2>
            <small>{{ $stats['available_books'] }} {{ __('menu.available_now') }}</small>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-info">
            <p class="stat-label">{{ __('menu.active_students') }}</p>
            <h2 class="stat-value">{{ number_format($stats['total_students']) }}</h2>
            <small>{{ __('menu.registered_members') }}</small>
        </div>
    </div>
    <div class="stat-card stat-yellow">
        <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
        <div class="stat-info">
            <p class="stat-label">{{ __('menu.active_borrows') }}</p>
            <h2 class="stat-value">{{ number_format($stats['total_borrowed']) }}</h2>
            <small>{{ $stats['borrowed_today'] }} {{ __('menu.currently_out') }}</small>
        </div>
    </div>
    <div class="stat-card stat-red">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <p class="stat-label">{{ __('menu.overdue') }}</p>
            <h2 class="stat-value">{{ number_format($stats['overdue_count']) }}</h2>
            <small>{{ __('menu.needs_attention') }}</small>
        </div>
    </div>
    <div class="stat-card stat-cyan">
        <div class="stat-icon"><i class="fas fa-tags"></i></div>
        <div class="stat-info">
            <p class="stat-label">{{ __('menu.categories') }}</p>
            <h2 class="stat-value">{{ number_format($stats['total_categories']) }}</h2>
            <small>{{ __('menu.book_category') }}</small>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> {{ __('menu.monthly_activity_full') }}</h3>
        </div>
        <div class="card-body" style="height:300px;position:relative;">
            <canvas id="borrowChart"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie"></i> {{ __('menu.book_category') }}</h3>
        </div>
        <div class="card-body" style="height:300px;position:relative;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- Top Books + Overdue --}}
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-trophy"></i> {{ __('menu.popular_books') }}</h3>
            <a href="{{ route('reports.popular') }}" class="btn btn-sm btn-outline-primary">{{ __('menu.view') }}</a>
        </div>
        <div class="card-body">
            @forelse($topBooks as $i => $book)
                <div class="rank-item">
                    <span class="rank-badge rank-{{ $i + 1 <= 3 ? $i + 1 : 'other' }}">{{ $i + 1 }}</span>
                    <div class="rank-info">
                        <p class="rank-title">{{ $book->title }}</p>
                        <small class="text-muted">{{ $book->author }}</small>
                    </div>
                    <span class="rank-count">{{ $book->borrows_count }}x</span>
                </div>
            @empty
                <p class="text-muted text-center py-4">{{ __('menu.no_borrow_data') }}</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> {{ __('menu.overdue_books') }}</h3>
            <a href="{{ route('reports.overdue') }}" class="btn btn-sm btn-outline-danger">{{ __('menu.view') }}</a>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('menu.students') }}</th>
                        <th>{{ __('menu.books') }}</th>
                        <th>{{ __('menu.due_date') }}</th>
                        <th>{{ __('menu.days_late') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueBorrows as $b)
                        <tr class="row-danger">
                            <td>{{ $b->student->name ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($b->book->title ?? '—', 20) }}</td>
                            <td>{{ $b->due_date->format('d/m/Y') }}</td>
                            <td class="text-danger font-bold">{{ now()->diffInDays($b->due_date) }}{{ __('menu.day_s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-success py-4">🎉 {{ __('menu.no_overdue_books') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> {{ __('menu.borrow_records') }}</h3>
        <a href="{{ route('borrows.index') }}" class="btn btn-sm btn-outline-primary">{{ __('menu.view') }}</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('menu.borrow_code') }}</th>
                    <th>{{ __('menu.students') }}</th>
                    <th>{{ __('menu.books') }}</th>
                    <th>{{ __('menu.borrow_date') }}</th>
                    <th>{{ __('menu.due_date') }}</th>
                    <th>{{ __('menu.book_status') }}</th>
                    <th>{{ __('menu.book_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBorrows as $b)
                    <tr class="{{ $b->isOverdue() ? 'row-danger' : '' }}">
                        <td class="text-mono text-sm">{{ $b->borrow_code }}</td>
                        <td>{{ $b->student->name ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($b->book->title ?? '—', 28) }}</td>
                        <td>{{ $b->borrow_date->format('d/m/Y') }}</td>
                        <td class="{{ $b->isOverdue() ? 'text-danger font-bold' : '' }}">
                            {{ $b->due_date->format('d/m/Y') }}
                        </td>
                        <td><span class="badge badge-{{ $b->status }}">{{ __('menu.status_' . $b->status) }}</span></td>
                        <td>
                            @if(in_array($b->status, ['borrowed','overdue']))
                                <a href="{{ route('borrows.return.form', $b) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-undo"></i> {{ __('menu.return_book') }}
                                </a>
                            @else
                                <a href="{{ route('borrows.show', $b) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">{{ __('menu.no_transactions') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
window.chartMonthlyData  = @json($chartData);
window.chartCategoryData = @json($categoryStats->map(fn($c) => ['name' => $c['name'], 'books' => $c['books']]));
</script>
@endpush
