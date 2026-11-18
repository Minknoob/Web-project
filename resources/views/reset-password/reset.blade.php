
@extends('layouts.app')

@section('title', 'Reset Password')

@section('navbar')
    <a href="{{ route('login') }}"
       class="btn btn-gc-outline rounded-pill px-3 px-md-4 py-2 fw-semibold">
        Sign in
    </a>
@endsection

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center px-3 py-5">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <div class="card bg-dark border border-secondary rounded-4 shadow-none"
             data-bs-theme="dark">

            <div class="card-body p-4 p-md-5">

                {{-- Header --}}
                <div class="text-center mb-4">

                    <h2 class="fw-bold text-light mb-2">Reset Password</h2>
                    <p class="text-secondary mb-0">
                        Create a new password to regain access to your account.
                    </p>
                </div>

                {{-- Reset Password Form --}}
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email"
                               class="form-label text-light fw-semibold small">
                            Email Address
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-black border-secondary text-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="5" width="18" height="14" rx="2"
                                          stroke="currentColor" stroke-width="1.7"/>
                                    <path d="m4 7 8 6 8-6"
                                          stroke="currentColor" stroke-width="1.7"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control bg-black text-light border-secondary py-2"
                                   placeholder="Enter your email"
                                   value="{{ old('email', $email) }}"
                                   autocomplete="email">
                        </div>

                        @error('email')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password"
                               class="form-label text-light fw-semibold small">
                            New Password
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-black border-secondary text-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="10" width="16" height="11" rx="2"
                                          stroke="currentColor" stroke-width="1.7"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"
                                          stroke="currentColor" stroke-width="1.7"
                                          stroke-linecap="round"/>
                                </svg>
                            </span>

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control bg-black text-light border-secondary py-2"
                                   placeholder="Enter your new password"
                                   autocomplete="new-password">
                        </div>

                        @error('password')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation"
                               class="form-label text-light fw-semibold small">
                            Confirm New Password
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-black border-secondary text-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m5 12.5 5 4.5 9-10"
                                          stroke="currentColor" stroke-width="1.8"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control bg-black text-light border-secondary py-2"
                                   placeholder="Confirm your new password"
                                   autocomplete="new-password">
                        </div>

                        {{-- Error Alert --}}
                        @php
                            $otherErrors = collect($errors->getBag('default')->getMessages())
                                ->except(['email', 'password'])
                                ->flatten();
                        @endphp

                        @if ($otherErrors->isNotEmpty())
                            <div class="alert alert-danger" role="alert">
                                {{ $otherErrors->first() }}
                            </div>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="btn btn-gc-primary w-100 rounded-3 py-2 fw-bold">
                        Reset Password
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}"
                       class="text-link-decor small fw-semibold">
                        ← Back to Sign in
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
