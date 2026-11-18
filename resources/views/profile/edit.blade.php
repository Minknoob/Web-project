
@extends('layouts.app')

@section('style')

    @vite(['resources/sass/edit.scss', 'resources/js/edit.js'])

@endsection

@section('content')
    @php

        $user = Auth::user();

    @endphp

    <div class="profile-page container py-5">

        <div class="profile-container mx-auto">

            

            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Avatar --}}
            <section class="profile-avatar-section">

                <div class="profile-avatar-wrapper">

                    <span 
                        id="avatarFallback"
                        class="{{ $user->avatar ? 'd-none' : '' }} profile-avatar profile-avatar-fallback
                                rounded-circle d-flex align-items-center justify-content-center fw-bold"
                        style="background: #c8ff19; color: #080a0e;">
                        
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>

                    <img
                        id="avatarPreview"
                        src="{{ $user->avatar
                            ? asset('storage/' . $user->avatar)
                            : '' }}"
                        alt="User avatar"
                        class="profile-avatar"
                        style="{{ $user->avatar ? '' : 'display:none' }}"
                    >

                    <button
                        type="button"
                        class="profile-avatar-edit"
                        aria-label="Change avatar"
                        title="Change avatar"
                        onclick="document.getElementById('avatarInput').click()"
                    >
                        {{-- Heroicons Camera --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="22" height="22"
                            viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M14 5H10L8 8H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-4l-2-3Z"/>
                            <circle cx="12" cy="14" r="3"/>
                        </svg>
                    </button>

                    <form
                        id="avatarForm"
                        action="{{ route('profile.avatar.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <input
                            type="file"
                            name="avatar"
                            id="avatarInput"
                            accept="image/png,image/jpeg,image/webp"
                            hidden
                        >
                    </form>

                </div>

                <h3 class="fw-semibold mb-1 mt-3">
                    {{ $user->name }}
                </h3>

                <h6 class="text-body-secondary small mb-0">
                    {{ $user->email }}
                </h6>

                @if($user->avatar)
                    <form
                        action="{{ route('profile.avatar.destroy') }}"
                        method="POST"
                        class="mt-2"
                        onsubmit="return confirm('Remove your current avatar?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none">
                            Remove avatar
                        </button>
                    </form>
                @endif

            </section>

            <hr class="profile-divider">

            {{-- Account information --}}
            
            <div class="profile-info-card">

                {{-- Header --}}
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Profile Settings</h2>
                    <p class="text-body-secondary mb-0">
                        Manage your personal information and account security.
                    </p>
                </div>

                {{-- Username --}}
                <div class="profile-field">
                    <label class="profile-label">Username</label>

                    <div class="profile-display" id="nameDisplay">
                        <input type="text" class="form-control profile-input"
                            value="{{ auth()->user()->name }}" readonly>
                        <button type="button" class="profile-edit-btn"
                                onclick="toggleEdit('name')">
                            {{-- Heroicon: pencil --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.121 2.121 0 0 1 3 3l-1.688 1.688m-3-3L7.5 13.849a4 4 0 0 0-1 1.697L5.5 19.5l3.954-1a4 4 0 0 0 1.697-1l9.71-9.71m-3-3 3 3"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST"
                        class="profile-edit-form d-none" id="nameForm">
                        @csrf
                        @method('PUT')

                        <input type="text" name="name"
                            class="form-control profile-input"
                            value="{{ auth()->user()->name }}" required>

                        <div class="profile-actions">
                            <button type="submit" class="btn profile-save-btn">Save</button>
                            <button type="button" class="btn profile-cancel-btn"
                                    onclick="toggleEdit('name')">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Email --}}
                <div class="profile-field">
                    <label class="profile-label">Email Address</label>

                    <div class="profile-display" id="emailDisplay">
                        <input type="email" class="form-control profile-input"
                            value="{{ auth()->user()->email }}" readonly>
                        <button type="button" class="profile-edit-btn"
                                onclick="toggleEdit('email')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.121 2.121 0 0 1 3 3l-1.688 1.688m-3-3L7.5 13.849a4 4 0 0 0-1 1.697L5.5 19.5l3.954-1a4 4 0 0 0 1.697-1l9.71-9.71m-3-3 3 3"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST"
                        class="profile-edit-form d-none" id="emailForm">
                        @csrf
                        @method('PUT')

                        <input type="email" name="email"
                            class="form-control profile-input"
                            value="{{ auth()->user()->email }}" required>

                        <div class="profile-actions">
                            <button type="submit" class="btn profile-save-btn">Save</button>
                            <button type="button" class="btn profile-cancel-btn"
                                    onclick="toggleEdit('email')">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Password --}}
                <div class="profile-field">
                    <label class="profile-label">Password</label>

                    <div class="profile-display" id="passwordDisplay">
                        <input type="password" class="form-control profile-input"
                            value="••••••••••••" readonly>
                        <a class="profile-edit-btn"
                            href="{{ route('password.request') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a2.121 2.121 0 0 1 3 3l-1.688 1.688m-3-3L7.5 13.849a4 4 0 0 0-1 1.697L5.5 19.5l3.954-1a4 4 0 0 0 1.697-1l9.71-9.71m-3-3 3 3"/>
                            </svg>
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
