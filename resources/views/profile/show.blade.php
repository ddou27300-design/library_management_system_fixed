@extends('layouts.app')
@section('title', __('menu.my_profile'))
@section('page-title', __('menu.profile_settings'))

@push('scripts')
<script>
function previewAvatar(input) {
    const circle = document.getElementById('avatar-circle');
    const fallback = document.getElementById('avatar-fallback');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            if (!circle) return;
            circle.src = e.target.result;
            circle.style.display = 'block';
            if (fallback) fallback.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@section('content')
<div class="row-2col" style="align-items:flex-start">
    {{-- Profile Info Card --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-circle"></i> {{ __('menu.my_profile') }}</h3>
        </div>
        <div class="card-body">
            <div class="student-profile">
                <div style="position:relative;display:inline-block;">
                    @if($user->avatar)
                        <img id="avatar-circle" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                             style="width:88px;height:88px;border-radius:50%;object-fit:cover;margin:0 auto 12px;box-shadow:0 4px 16px rgba(26,60,94,.2);display:block;">
                        <div id="avatar-fallback" class="avatar-lg" style="background:linear-gradient(135deg,var(--primary),var(--accent));width:88px;height:88px;font-size:34px;display:none;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @else
                        <img id="avatar-circle" src="" alt="Avatar"
                             style="width:88px;height:88px;border-radius:50%;object-fit:cover;margin:0 auto 12px;box-shadow:0 4px 16px rgba(26,60,94,.2);display:none;">
                        <div id="avatar-fallback" class="avatar-lg" style="background:linear-gradient(135deg,var(--primary),var(--accent));width:88px;height:88px;font-size:34px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h3 style="font-family:var(--font-display);font-size:1.2rem;margin:8px 0 2px;">{{ $user->name }}</h3>
                <span class="badge {{ $user->role === 'admin' ? 'badge-warning' : 'badge-info' }}">
                    <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : 'fa-book' }}"></i>
                    {{ __('menu.role_' . $user->role) }}
                </span>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">{{ __('menu.full_name') }} <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __('menu.email') }} <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="avatar">{{ $user->avatar ? __('menu.change_avatar') : __('menu.upload_avatar') }}</label>
                    <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*"
                           onchange="previewAvatar(this)" style="padding:5px;">
                    <span class="form-hint">{{ __('menu.avatar_hint') }}</span>
                </div>

                <div class="form-actions" style="border:none;padding:0;margin-top:20px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('menu.update_profile') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Card --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-lock"></i> {{ __('menu.change_password') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">{{ __('menu.current_password') }} <span class="required">*</span></label>
                    <input type="password" id="current_password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           placeholder="{{ __('menu.current_password') }}" required>
                    @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-row form-row-2">
                    <div class="form-group">
                        <label for="password">{{ __('menu.new_password') }} <span class="required">*</span></label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ __('menu.password_min') }}" required>
                        @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('menu.confirm_new_password') }} <span class="required">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control"
                               placeholder="{{ __('menu.password_placeholder_repeat') }}" required>
                    </div>
                </div>

                <div class="form-actions" style="border:none;padding:0;margin-top:20px;">
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-key"></i> {{ __('menu.change_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Avatar History --}}
<div class="card" style="margin-top:0;">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> {{ __('menu.avatar_history') }}</h3>
        @if(!empty($user->old_avatars))
            <span class="badge badge-info">{{ count($user->old_avatars) }} {{ __('menu.records') }}</span>
        @endif
    </div>
    <div class="card-body">
        @php $avatars = $user->old_avatars ?? []; @endphp
        @if(!empty($avatars))
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:16px;">
                @foreach($avatars as $index => $av)
                    <div style="text-align:center;">
                        <img src="{{ asset('storage/' . $av) }}" alt="Old avatar"
                             style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin-bottom:8px;box-shadow:0 2px 8px rgba(26,60,94,.12);border:2px solid var(--border);">
                        <div style="display:flex;gap:4px;justify-content:center;">
                            <form action="{{ route('profile.avatar.restore', $index) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-accent" style="font-size:.75rem;padding:4px 10px;">
                                    <i class="fas fa-undo"></i> {{ __('menu.restore') }}
                                </button>
                            </form>
                            <form action="{{ route('profile.avatar.delete', $index) }}" method="POST"
                                  data-confirm="{{ json_encode(['message' => 'Delete this avatar permanently?']) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:.75rem;padding:4px 10px;">
                                    <i class="fas fa-trash"></i> {{ __('menu.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-camera"></i>
                <p>{{ __('menu.no_avatar_history') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
