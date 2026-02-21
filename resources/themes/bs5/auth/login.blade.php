@extends('bs5::layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none">
                        <span class="fs-3 fw-bold text-primary">
                            <i class="fa-solid fa-layer-group me-2"></i>{{ site_name() }}
                        </span>
                    </a>
                    <p class="text-muted small mt-1">{{ site_tagline() }}</p>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-1">Welcome back</h4>
                        <p class="text-muted small mb-4">Sign in to your account</p>

                        <form method="POST" action="/login">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Email address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           placeholder="you@example.com" required autofocus>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold small mb-0">Password</label>
                                    <a href="/forgot-password" class="small text-primary text-decoration-none">Forgot?</a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" id="password"
                                           class="form-control border-start-0 ps-0" required>
                                    <button type="button" class="btn btn-outline-secondary border-start-0"
                                            onclick="const p=document.getElementById('password');p.type=p.type==='password'?'text':'password';this.querySelector('i').classList.toggle('fa-eye');this.querySelector('i').classList.toggle('fa-eye-slash')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small" for="remember">Keep me signed in</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                            </button>
                        </form>

                        <hr class="my-4">

                        <p class="text-center small text-muted mb-0">
                            Don't have an account?
                            <a href="/register" class="text-primary fw-semibold text-decoration-none">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
