@extends('layouts.app')
@section('title', 'Students')
@section('page-title', __('menu.students'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-graduate"></i> {{ __('menu.students') }}</h3>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> {{ __('menu.add_new') }}
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('students.index') }}" class="filter-form">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name, ID, email..." class="form-control search-input">

            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                <option value="inactive"  {{ request('status') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            @if($majors->isNotEmpty())
            <select name="major" class="form-control">
                <option value="">All Majors</option>
                @foreach($majors as $major)
                    <option value="{{ $major }}" {{ request('major') === $major ? 'selected' : '' }}>
                        {{ $major }}
                    </option>
                @endforeach
            </select>
            @endif

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> {{ __('menu.search') }}
            </button>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                {{ __('menu.reset') }}
            </a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class / Major</th>
                    <th>Contact</th>
                    <th>Borrowing</th>
                    <th>Status</th>
                    <th>{{ __('menu.book_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="text-mono fw-600">{{ $student->student_id }}</td>
                        <td>
                            <a href="{{ route('students.show', $student) }}" class="fw-600 text-primary">
                                {{ $student->name }}
                            </a>
                        </td>
                        <td>
                            <span>{{ $student->class ?? '—' }}</span>
                            @if($student->major)
                                <br><small class="text-muted">{{ $student->major }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $student->email ?? '—' }}
                            @if($student->phone)
                                <br><small class="text-muted">{{ $student->phone }}</small>
                            @endif
                        </td>
                        <td>
                            @if($student->active_borrows_count > 0)
                                <span class="badge badge-warning">{{ $student->active_borrows_count }} active</span>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match($student->status) {
                                    'active'    => 'badge-success',
                                    'inactive'  => 'badge-default',
                                    'suspended' => 'badge-danger',
                                    default     => 'badge-default'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ ucfirst($student->status) }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete student {{ addslashes($student->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                        {{ $student->active_borrows_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding:40px">
                            <i class="fas fa-user-graduate" style="font-size:2rem;opacity:.3;display:block;margin-bottom:10px"></i>
                            No students found. <a href="{{ route('students.create') }}">Register one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}
            of {{ $students->total() }} students
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection
