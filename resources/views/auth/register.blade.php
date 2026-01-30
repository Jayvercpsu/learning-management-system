<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus fa-3x text-primary"></i>
                            <h3 class="mt-3">Create Account</h3>
                            <p class="text-muted">Join our learning platform</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST" id="registerForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" id="passwordInput"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                                <div class="password-requirements">
                                    <small class="requirement" id="req-length">
                                        <i class="fas fa-times-circle"></i> At least 8 characters
                                    </small><br>
                                    <small class="requirement" id="req-uppercase">
                                        <i class="fas fa-times-circle"></i> One uppercase letter
                                    </small><br>
                                    <small class="requirement" id="req-lowercase">
                                        <i class="fas fa-times-circle"></i> One lowercase letter
                                    </small><br>
                                    <small class="requirement" id="req-number">
                                        <i class="fas fa-times-circle"></i> One number
                                    </small><br>
                                    <small class="requirement" id="req-special">
                                        <i class="fas fa-times-circle"></i> One special character (@$!%*?&)
                                    </small>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <select name="role" id="roleSelect"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Select Role</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher
                                    </option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Note: Teacher accounts require admin approval</small>
                            </div>

                            <div id="studentFields" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Student ID</label>
                                    <input type="text" name="student_id"
                                        class="form-control @error('student_id') is-invalid @enderror"
                                        value="{{ old('student_id') }}">
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <input type="text" name="course"
                                        class="form-control @error('course') is-invalid @enderror"
                                        value="{{ old('course') }}">
                                    @error('course')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <input type="text" name="section"
                                        class="form-control @error('section') is-invalid @enderror"
                                        value="{{ old('section') }}">
                                    @error('section')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3" id="registerBtn">
                                <span id="btnText">
                                    <i class="fas fa-user-plus"></i> Register
                                </span>
                                <span id="btnLoading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Registering...
                                </span>
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="successModal" class="success-modal">
        <div class="success-content">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
            <h3 class="text-success mb-3">Registration Successful!</h3>
            <p id="successMessage" class="text-muted mb-0"></p>
            <p class="text-muted mt-2">Redirecting to login...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/register.js') }}"></script>
</body>

</html>