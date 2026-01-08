{{-- resources/views/Doctor/Profile/change-password.blade.php --}}
@extends('admin.admin-doctor-layout.app')

@section('title', 'Change Password')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Change Password</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </nav>
        </div>

        <div class="container py-4">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Dynamic JS validation message --}}
            <div id="formAlert" class="alert alert-danger d-none" role="alert"></div>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">

                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-body p-4">

                            <h4 class="card-title text-center mb-4 fw-semibold text-primary">Update Your Password</h4>

                            <form id="changePasswordForm" method="POST"
                                action="{{ route('doctor.profile.password.update') }}" novalidate>
                                @csrf

                                {{-- Current Password --}}
                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror" required
                                            autocomplete="current-password" placeholder="Enter current password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="toggleCurrentPwd">Show</button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- New Password --}}
                                <div class="mb-3">
                                    <label for="new_password" class="form-label fw-semibold">New Password</label>
                                    <div class="input-group">
                                        <input type="password" id="new_password" name="new_password"
                                            class="form-control @error('new_password') is-invalid @enderror" required
                                            autocomplete="new-password" placeholder="Create a new password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="toggleNewPwd">Show</button>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <div class="mt-2 small text-muted">
                                        Use at least 8 characters with uppercase, lowercase, number, and symbol.
                                    </div>

                                    {{-- Password Checklist --}}
                                    <ul id="pwdChecklist" class="list-unstyled small mt-2 mb-0" style="line-height: 1.6;">
                                        <li><span id="chkLength" class="text-muted">●</span> Minimum 8 characters</li>
                                        <li><span id="chkUpper" class="text-muted">●</span> Uppercase letter (A–Z)</li>
                                        <li><span id="chkLower" class="text-muted">●</span> Lowercase letter (a–z)</li>
                                        <li><span id="chkNumber" class="text-muted">●</span> Number (0–9)</li>
                                        <li><span id="chkSymbol" class="text-muted">●</span> Symbol (e.g. !@#$%)</li>
                                    </ul>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New
                                        Password</label>
                                    <div class="input-group">
                                        <input type="password" id="new_password_confirmation"
                                            name="new_password_confirmation"
                                            class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                            required autocomplete="new-password" placeholder="Re-enter new password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="toggleConfirmPwd">Show</button>
                                    </div>
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div id="confirmHelp" class="text-danger small mt-2" style="display:none;"></div>
                                </div>

                                <div class="d-grid">
                                    <button id="submitBtn" type="submit" class="btn btn-primary btn-lg rounded-3">
                                        Update Password
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
@endsection

@push('styles')
    <style>
        .card-title {
            color: #2c3e50;
        }

        #pwdChecklist span {
            display: inline-block;
            width: 18px;
            text-align: center;
            margin-right: 6px;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            // --- Toggle password visibility ---
            function toggle(btnId, inputId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                if (!btn || !input) return;
                btn.addEventListener('click', () => {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    btn.textContent = isHidden ? 'Hide' : 'Show';
                });
            }

            toggle('toggleCurrentPwd', 'current_password');
            toggle('toggleNewPwd', 'new_password');
            toggle('toggleConfirmPwd', 'new_password_confirmation');

            const form = document.getElementById('changePasswordForm');
            const currentPwd = document.getElementById('current_password');
            const newPwd = document.getElementById('new_password');
            const confirmPwd = document.getElementById('new_password_confirmation');
            const confirmHelp = document.getElementById('confirmHelp');
            const formAlert = document.getElementById('formAlert');

            const chkLength = document.getElementById('chkLength');
            const chkUpper = document.getElementById('chkUpper');
            const chkLower = document.getElementById('chkLower');
            const chkNumber = document.getElementById('chkNumber');
            const chkSymbol = document.getElementById('chkSymbol');

            // Password strength check
            function testPassword(pw) {
                return {
                    length: pw.length >= 8,
                    upper: /[A-Z]/.test(pw),
                    lower: /[a-z]/.test(pw),
                    number: /[0-9]/.test(pw),
                    symbol: /[!@#\$%\^&\*\(\)\-_\=\+\[\]\{\};:'",<\.>\/\?\\|`~]/.test(pw)
                };
            }

            function updateChecklist(res) {
                const update = (el, valid) => {
                    el.className = valid ? 'text-success' : 'text-muted';
                    el.textContent = valid ? '✓' : '●';
                };
                update(chkLength, res.length);
                update(chkUpper, res.upper);
                update(chkLower, res.lower);
                update(chkNumber, res.number);
                update(chkSymbol, res.symbol);
            }

            function canSubmit() {
                const pw = newPwd.value.trim();
                const confirm = confirmPwd.value.trim();
                const r = testPassword(pw);
                const allGood = r.length && r.upper && r.lower && r.number && r.symbol;
                const match = pw && confirm && pw === confirm;

                if (confirm && !match) {
                    confirmHelp.style.display = 'block';
                    confirmHelp.textContent = 'Passwords do not match.';
                } else if (!confirm && pw) {
                    confirmHelp.style.display = 'block';
                    confirmHelp.textContent = 'Please confirm your new password.';
                } else {
                    confirmHelp.style.display = 'none';
                    confirmHelp.textContent = '';
                }

                return allGood && match;
            }

            newPwd.addEventListener('input', () => updateChecklist(testPassword(newPwd.value)));
            confirmPwd.addEventListener('input', canSubmit);

            // --- Submit handler ---
            form.addEventListener('submit', (e) => {
                formAlert.classList.add('d-none');
                formAlert.textContent = '';

                // 1️⃣ Check empty fields
                if (!currentPwd.value.trim() || !newPwd.value.trim() || !confirmPwd.value.trim()) {
                    e.preventDefault();
                    formAlert.classList.remove('d-none');
                    formAlert.textContent = '⚠️ All fields are required.';
                    window.scrollTo({
                        top: formAlert.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    return;
                }

                // 2️⃣ Check password validity
                if (!canSubmit()) {
                    e.preventDefault();
                    formAlert.classList.remove('d-none');
                    formAlert.textContent =
                        'Please meet all password requirements and ensure both fields match.';
                    window.scrollTo({
                        top: formAlert.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    return;
                }

                // 3️⃣ Allow valid submission
                e.target.querySelector('#submitBtn').textContent = 'Updating...';
            });
        })();
    </script>
@endpush
