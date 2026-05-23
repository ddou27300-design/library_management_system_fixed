@extends('layouts.app')
@section('title', __('menu.students'))
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
                   placeholder="{{ __('menu.search_students') }}"
                   class="form-control search-input">

            <select name="status" class="form-control">
                <option value="">{{ __('menu.all_status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('menu.status_active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('menu.status_inactive') }}</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('menu.student_suspended') }}</option>
            </select>

            @if($majors->isNotEmpty())
            <select name="major" class="form-control">
                <option value="">{{ __('menu.all_majors') }}</option>
                @foreach($majors as $major)
                    <option value="{{ $major }}">{{ $major }}
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
                    <th>{{ __('menu.student_id') }}</th>
                    <th>{{ __('menu.name') }}</th>
                    <th>{{ __('menu.class_major') }}</th>
                    <th>{{ __('menu.contact') }}</th>
                    <th>{{ __('menu.borrowing') }}</th>
                    <th>{{ __('menu.status') }}</th>
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
                                <span class="badge badge-warning">
                                    {{ $student->active_borrows_count }} {{ __('menu.active') }}
                                </span>
                            @else
                                <span class="text-muted">{{ __('menu.none') }}</span>
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
                            <span class="badge {{ $statusClass }}">
                                {{ __('menu.status_' . $student->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="{{ __('menu.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="{{ __('menu.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('{{ __('menu.delete_student_confirm', ['name' => addslashes($student->name)]) }}')">
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
                            {{ __('menu.no_students') }}
                            <a href="{{ route('students.create') }}">{{ __('menu.register_one') }}</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            {{ __('menu.showing_results', ['from' => $students->firstItem() ?? 0, 'to' => $students->lastItem() ?? 0, 'total' => $students->total()]) }}
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection