<div class="row g-3 mb-3">
    <div class="{{ isset($compact) ? 'col-6' : 'col-md-4' }}">
        <label class="form-label small fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="author_name" class="form-control form-control-sm @error('author_name') is-invalid @enderror"
               value="{{ old('author_name', auth()->user()?->name) }}" required>
        @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="{{ isset($compact) ? 'col-6' : 'col-md-4' }}">
        <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="author_email" class="form-control form-control-sm @error('author_email') is-invalid @enderror"
               value="{{ old('author_email', auth()->user()?->email) }}" required>
        @error('author_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @if(!isset($compact))
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Website</label>
        <input type="url" name="author_url" class="form-control form-control-sm"
               value="{{ old('author_url', auth()->user()?->website) }}"
               placeholder="https://...">
    </div>
    @endif
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Comment <span class="text-danger">*</span></label>
    <textarea name="content" rows="{{ isset($compact) ? 2 : 4 }}"
              class="form-control form-control-sm @error('content') is-invalid @enderror"
              required>{{ old('content') }}</textarea>
    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa-solid fa-paper-plane me-1"></i>{{ isset($compact) ? 'Post Reply' : 'Post Comment' }}
</button>
