@extends('bs5::layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none">
                        <span class="fs-3 fw-bold text-primary">
                            <i class="fa-solid fa-layer-group me-2"></i>{{ site_name() }}
                        </span>
                    </a>
                    <p class="text-muted small mt-1">Create your account</p>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-1">Join {{ site_name() }}</h4>
                        <p class="text-muted small mb-4">Fill in the form below to get started</p>

                        <form method="POST" action="/register">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                           class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                           placeholder="Your name" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Email address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           placeholder="you@example.com" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" id="reg-pass"
                                           class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                           placeholder="Min. 8 characters" required>
                                    <button type="button" class="btn btn-outline-secondary border-start-0"
                                            onclick="const p=document.getElementById('reg-pass');p.type=p.type==='password'?'text':'password';this.querySelector('i').classList.toggle('fa-eye');this.querySelector('i').classList.toggle('fa-eye-slash')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock-open text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation"
                                           class="form-control border-start-0 ps-0"
                                           placeholder="Repeat password" required>
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    I agree to the <a href="/terms" class="text-primary">Terms of Service</a> and <a href="/privacy" class="text-primary">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                <i class="fa-solid fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>

                        <hr class="my-4">

                        <p class="text-center small text-muted mb-0">
                            Already have an account?
                            <a href="/login" class="text-primary fw-semibold text-decoration-none">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
