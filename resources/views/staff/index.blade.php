@extends('layouts.app')
@section('title', 'Staff Management')
@section('page-title', 'Staff Management')

@push('styles')
<style>
.role-admin     { background:#fef3c7;color:#d97706;border:1px solid #fcd34d; }
.role-librarian { background:#dbeafe;color:#2563eb;border:1px solid #93c5fd; }
.you-badge { background:#dcfce7;color:#16a34a;border:1px solid #86efac;font-size:.7rem;padding:2px 7px;border-radius:20px;margin-left:6px;font-weight:600; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users-cog"></i> Staff Accounts</h3>
        <a href="{{ route('staff.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Staff
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $i => $user)
                <tr>
                    <td class="text-muted">{{ ($staff->currentPage()-1) * $staff->perPage() + $i + 1 }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === Auth::id())
                            <span class="you-badge">You</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'role-admin' : 'role-librarian' }}">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : 'fa-book' }}"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('staff.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('staff.destroy', $user) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Remove {{ addslashes($user->name) }} from staff?')">
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
                        No staff accounts found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            Showing {{ $staff->firstItem() ?? 0 }}–{{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }} staff
        </div>
        {{ $staff->links() }}
    </div>
</div>
@endsection
