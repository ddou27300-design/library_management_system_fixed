@extends('layouts.app')
@section('title', __('menu.staff_management'))
@section('page-title', __('menu.staff_management'))

@push('styles')
<style>
.role-admin     { background:var(--warning-light);color:var(--warning);border:1px solid #f0d8a0; }
.role-librarian { background:var(--primary-soft);color:var(--primary);border:1px solid #b8d1ed; }
.you-badge { background:var(--success-light);color:var(--success);border:1px solid #a3d9bf;font-size:.7rem;padding:2px 7px;border-radius:20px;margin-left:6px;font-weight:600; }
@media (max-width: 480px) {
    .staff-table th:nth-child(1), .staff-table td:nth-child(1),
    .staff-table th:nth-child(5), .staff-table td:nth-child(5),
    .staff-table th:nth-child(3), .staff-table td:nth-child(3) { display: none; }
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users-cog"></i> {{ __('menu.staff_accounts') }}</h3>
        <a href="{{ route('staff.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> {{ __('menu.add_staff') }}
        </a>
    </div>

    <div class="card-body p-0 table-wrap staff-table">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('menu.name') }}</th>
                    <th>{{ __('menu.email') }}</th>
                    <th>{{ __('menu.role') }}</th>
                    <th>{{ __('menu.joined') }}</th>
                    <th>{{ __('menu.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $i => $user)
                <tr>
                    <td class="text-muted">{{ ($staff->currentPage()-1) * $staff->perPage() + $i + 1 }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === Auth::id())
                            <span class="you-badge">{{ __('menu.you') }}</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'role-admin' : 'role-librarian' }}">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : 'fa-book' }}"></i>
                            {{ __('menu.role_' . $user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('staff.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="{{ __('menu.edit') }}"
                               data-confirm="{{ json_encode(['message' => __('menu.edit_staff_confirm', ['name' => $user->name]), 'icon' => 'warning', 'accent' => true]) }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('staff.destroy', $user) }}" method="POST" style="display:inline"
                                  data-confirm="{{ json_encode(['message' => __('menu.delete_staff_confirm', ['name' => $user->name])]) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted" style="padding:40px">
                        {{ __('menu.no_staff_found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            {{ __('menu.showing_results', ['from' => $staff->firstItem() ?? 0, 'to' => $staff->lastItem() ?? 0, 'total' => $staff->total()]) }}
        </div>
        {{ $staff->links() }}
    </div>
</div>
@endsection
